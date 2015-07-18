<?php
/**
 * User: Andy
 * Date: 26/01/15
 * Time: 13:34
 */

namespace AVCMS\Bundles\AvaImporter\Controller;

use AV\Cache\CacheClearer;
use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Adverts\Model\Advert;
use AVCMS\Bundles\Blog\Model\BlogPost;
use AVCMS\Bundles\Comments\Model\Comment;
use AVCMS\Bundles\Friends\Model\Friend;
use AVCMS\Bundles\Games\Model\Game;
use AVCMS\Bundles\Games\Model\GameCategory;
use AVCMS\Bundles\LikeDislike\Model\Rating;
use AVCMS\Bundles\Links\Model\Link;
use AVCMS\Bundles\Pages\Model\Page;
use AVCMS\Bundles\PrivateMessages\Model\PrivateMessage;
use AVCMS\Bundles\Tags\Model\Tag;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvaImporterController extends Controller
{
    protected $connection;

    public function importerHomeAction(Request $request)
    {
        $databaseForm = new FormBlueprint();
        $databaseForm->add('database_host', 'text', ['label' => 'Database Host']);
        $databaseForm->add('database_database', 'text', ['label' => 'Database Name']);
        $databaseForm->add('database_username', 'text', ['label' => 'Database Username']);
        $databaseForm->add('database_password', 'password', ['label' => 'Database Password']);

        $form = $this->buildForm($databaseForm, $request);

        if ($form->isValid()) {
            $databaseConfig = array(
                'driver' => 'mysql', // Db driver
                'charset' => 'utf8', // Optional
                'collation' => 'utf8_unicode_ci', // Optional
            );

            $databaseConfig['host'] = $form->getData('database_host');
            $databaseConfig['database'] = $form->getData('database_database');
            $databaseConfig['username'] = $form->getData('database_username');
            $databaseConfig['password'] = $form->getData('database_password');
            $databaseConfig['prefix'] = 'ava_';

            try {
                $s = new \Pixie\Connection('mysql', $databaseConfig);
            } catch (\PDOException $e) {
                $form->addCustomErrors([new FormError('all', 'The database details you entered to not appear to be valid.')]);
                $form->addCustomErrors([new FormError('all', $e->getMessage())]);
            }

            if (isset($s)) {
                try {
                    $s->getQueryBuilder()->table('games')->count();
                }
                catch (\PDOException $e) {
                    $form->addCustomErrors([new FormError('database_database', 'Cannot find AV Arcade install in that database')]);
                    $form->addCustomErrors([new FormError('all', $e->getMessage())]);
                }
            }

            if ($form->isValid()) {
                $session = $this->container->get('session');
                $session->set('database_host', $databaseConfig['host']);
                $session->set('database_database', $databaseConfig['database']);
                $session->set('database_username', $databaseConfig['username']);
                $session->set('database_password', $databaseConfig['password']);

                if (!$request->get('fix_points')) {

                    $qb = $this->container->get('query_builder');
                    $qb->table('games')->delete();
                    $qb->table('game_categories')->delete();
                    $qb->table('tags')->delete();
                    $qb->table('tag_taxonomy')->delete();
                    $qb->table('users')->whereNot('id', 1)->delete();
                    $qb->table('comments')->delete();
                    $qb->table('blog_posts')->delete();
                    $qb->table('pages')->delete();
                    $qb->table('links')->delete();
                    $qb->table('adverts')->delete();
                    $qb->table('ratings')->delete();
                    $qb->table('messages')->delete();
                    $qb->table('friends')->delete();

                    return new RedirectResponse($this->generateUrl('ava_importer_run'));
                }
                else {
                    return new RedirectResponse($this->generateUrl('ava_importer_fix_points_run'));
                }
            }
        }


        return new Response($this->render('@AvaImporter/admin/import_home.twig', ['form' => $form->createView(), 'fix_points' => $request->get('fix_points')]));
    }

    public function doImportAction(Request $request)
    {
        $session = $this->container->get('session');

        if (!$session->has('database_host')) {
            $this->redirect('ava_importer_home');
        }

        $stages = ['games', 'game_categories', 'tags', 'tag_relations', 'users', 'game_comments', 'news', 'news_comments', 'pages', 'links', 'adverts', 'ratings', 'messages', 'friends'];

        $importPerRun = 1000;
        $stage = $request->get('stage', $stages[0]);
        $run = $request->get('run', 1);

        $offset = $run * $importPerRun - $importPerRun;

        $totalImported = 0;

        $message = null;

        $oldSiteUrl = $this->getQB('settings')->where('name', 'site_url')->first()['value'];

        $oldSiteInstallDate = (new \DateTime($this->getQB('users')->where('id', 1)->first()['joined']))->getTimestamp();

        if ($stage === 'games') {
            // GAMES //
            $games = $this->getQB('games')->limit($importPerRun)->offset($offset)->get();

            $gamesProcessed = [];
            foreach ($games as $gm) {
                $this->renameFields($gm, [
                    'seo_url' => 'slug',
                    'display' => 'resize_type',
                    'submitter' => 'submitter_id',
                    'url' => 'file',
                    'image' => 'thumbnail',
                    'html_code' => 'embed_code'
                ]);

                $gm['file'] = str_replace($oldSiteUrl.'/games/', '', $gm['file']);
                $gm['thumbnail'] = str_replace($oldSiteUrl.'/games/images/', '', $gm['thumbnail']);
                $gm['creator_id'] = $this->activeUser()->getId();

                if ($gm['date_added'] !== '0000-00-00 00:00:00') {
                    $gm['date_added'] = $gm['publish_date'] = (new \DateTime($gm['date_added']))->getTimestamp();
                }
                else {
                    $gm['date_added'] = $gm['publish_date'] = $oldSiteInstallDate;
                }

                if (!$gm['slug']) {
                    $gm['slug'] = $this->get('slug.generator')->slugify($gm['name']).'-'.time();
                }

                if ($gm['import'] == 1) {
                    $file = $gm['file'];
                    $gm['file'] .= '.swf';
                    $gm['thumbnail'] = $file.'.png';
                }

                $game = new Game();
                $game->fromArray($gm, true);

                $gamesProcessed[] = $game;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Games Imported (Run '.$run.')';

            if ($totalImported) {
                $this->model('Games')->insert($gamesProcessed);
            }
        }
        elseif ($stage === 'game_categories') {
            // GAME CATEGORIES //
            $categories = $this->getQB('cats')->limit($importPerRun)->offset($offset)->get();

            $catsProcessed = [];
            foreach ($categories as $cat) {
                $this->renameFields($cat, [
                    'cat_order' => 'order',
                    'seo_url' => 'slug',
                    'parent_id' => 'parent',
                ]);

                $this->unencodeFields($cat, ['name', 'description']);

                if ($cat['parent'] == 0) {
                    $cat['parent'] = null;
                }

                $nuCat = new GameCategory();
                $nuCat->fromArray($cat, true);

                $catsProcessed[] = $nuCat;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Categories Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Games:GameCategories')->insert($catsProcessed);
        }
        elseif ($stage === 'tags') {
            // TAGS //
            $tags = $this->getQB('tags')->limit($importPerRun)->offset($offset)->get();

            $tagsProcessed = [];
            foreach ($tags as $t) {
                $this->renameFields($t, [
                    'tag_name' => 'name',
                ]);

                $tag = new Tag();
                $tag->fromArray($t, true);

                $tagsProcessed[] = $tag;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Tags Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Tags:TagsModel')->insert($tagsProcessed);
        }
        elseif ($stage === 'tag_relations') {
            // TAG RELATIONS //
            $relations = $this->getQB('tag_relations')->limit($importPerRun)->offset($offset)->get();

            foreach ($relations as $r) {
                $this->renameFields($r, [
                    'tag_id' => 'taxonomy_id',
                    'game_id' => 'content_id'
                ]);

                $r['content_type'] = 'game';

                unset($r['id']);

                $this->model('Tags:TagsTaxonomyModel')->query()->insert($r);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Tag relations Imported (Run '.$run.')';
        }
        elseif ($stage == 'users') {
            $usersProcessed = [];
            $users = $this->getQB('users')->limit($importPerRun)->offset($offset)->get();

            foreach ($users as $u) {
                if ($u['id'] == 1) {
                    $totalImported++;
                    continue;
                }

                $this->renameFields($u, [
                    'seo_url' => 'slug'
                ]);

                if ($u['password_bcrypt']) {
                    $u['password'] = $u['password_bcrypt'];
                }

                if ($u['admin'] == 1) {
                    $u['role_list'] = 'ROLE_SUPER_ADMIN';
                }

                $u['joined'] = (new \DateTime($u['joined']))->getTimestamp();
                $u['last_activity'] = (new \DateTime($u['last_activity']))->getTimestamp();

                $newUser = $this->model('Users')->newEntity();
                $newUser->fromArray($u, true);
                $newUser->points->setPoints($u['points']);

                $usersProcessed[] = $newUser;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Users Imported (Run '.$run.')';

            if ($totalImported && $usersProcessed)
                $this->model('Users')->insert($usersProcessed);
        }
        elseif ($stage == 'game_comments') {
            $commentsProcessed = [];
            $comments = $this->getQB('comments')->limit($importPerRun)->offset($offset)->get();

            foreach ($comments as $c) {
                $this->renameFields($c, ['link_id' => 'content_id', 'user' => 'user_id']);
                $this->unencodeFields($c, ['comment']);
                $c['content_type'] = 'game';

                $games = $this->model('Games');
                $game = $games->query()->where('id', $c['content_id'])->select(['name', 'comments'])->first();
                if ($game) {
                    $c['content_title'] = $game->getName();
                }
                else {
                    $c['content_title'] = 'No longer exists';
                }

                $c['date'] = (new \DateTime($c['date']))->getTimestamp();

                unset($c['id']);

                $newComment = new Comment();
                $newComment->fromArray($c, true);

                $commentsProcessed[] = $newComment;

                $games->query()->where('id', $c['content_id'])->update(['comments' => $games->query()->raw('comments + 1')]);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Game Comments Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Comments')->insert($commentsProcessed);
        }
        elseif ($stage == 'news') {
            $newsProcessed = [];
            $news = $this->getQB('news')->limit($importPerRun)->offset($offset)->get();

            foreach ($news as $n) {
                $this->renameFields($n, [
                    'content' => 'body',
                    'user' => 'user_id',
                    'date' => 'date_added',
                    'seo_url' => 'slug'
                ]);
                $this->unencodeFields($n, ['body']);

                $n['creator_id'] = $n['user_id'];

                $n['publish_date'] = ($n['date_added'] ? (new \DateTime($n['date_added']))->getTimestamp() : time());

                $blogPost = new BlogPost();
                $blogPost->fromArray($n, true);

                $newsProcessed[] = $blogPost;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Blog Posts Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Blog:BlogPosts')->insert($newsProcessed);
        }
        elseif ($stage == 'news_comments') {
            $commentsProcessed = [];
            $comments = $this->getQB('news_comments')->limit($importPerRun)->offset($offset)->get();

            foreach ($comments as $c) {
                $this->renameFields($c, ['link_id' => 'content_id', 'user' => 'user_id']);
                $this->unencodeFields($c, ['comment']);
                $c['content_type'] = 'blog_post';

                $blogPosts = $this->model('Blog:BlogPosts');
                $game = $blogPosts->query()->where('id', $c['content_id'])->select(['title', 'comments'])->first();
                if ($game) {
                    $c['content_title'] = $game->getTitle();
                }
                else {
                    $c['content_title'] = 'No longer exists';
                }

                $c['date'] = (new \DateTime($c['date']))->getTimestamp();

                unset($c['id']);

                $newComment = new Comment();
                $newComment->fromArray($c, true);

                $commentsProcessed[] = $newComment;

                $blogPosts->query()->where('id', $c['content_id'])->update(['comments' => $blogPosts->query()->raw('comments + 1')]);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' News Comments Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Comments')->insert($commentsProcessed);
        }
        elseif ($stage == 'pages') {
            $pagesProcessed = [];
            $pages = $this->getQB('pages')->limit($importPerRun)->offset($offset)->get();

            foreach ($pages as $p) {
                $this->renameFields($p, [
                    'page' => 'content',
                    'name' => 'title',
                    'seo_url' => 'slug'
                ]);
                $this->unencodeFields($p, ['content']);

                $p['creator_id'] = 1;

                $p['publish_date'] = $p['date_added'] = time();
                $p['published'] = 1;

                $page = new Page();
                $page->fromArray($p, true);

                $pagesProcessed[] = $page;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Pages Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Pages')->insert($pagesProcessed);
        }
        elseif ($stage == 'links') {
            $linksProcessed = [];
            $links = $this->getQB('links')->limit($importPerRun)->offset($offset)->get();

            foreach ($links as $l) {
                $this->renameFields($l, [
                    'name' => 'anchor',
                ]);

                $l['published'] = 1;
                $l['date_added'] = time();
                $l['admin_seen'] = 1;

                $link = new Link();
                $link->fromArray($l, true);

                $linksProcessed[] = $link;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Links Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Links')->insert($linksProcessed);
        }
        elseif ($stage == 'adverts') {
            $advertsProcessed = [];
            $adverts = $this->getQB('adverts')->limit($importPerRun)->offset($offset)->get();

            foreach ($adverts as $a) {
                $this->renameFields($a, [
                    'ad_name' => 'name',
                    'ad_content' => 'content',
                ]);

                $advert = new Advert();
                $advert->fromArray($a, true);

                $advertsProcessed[] = $advert;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Adverts Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('Adverts')->insert($advertsProcessed);
        }
        elseif ($stage == 'ratings') {
            $ratingsProcessed = [];
            $ratings = $this->getQB('ratings')->limit($importPerRun)->offset($offset)->get();

            foreach ($ratings as $r) {
                $this->renameFields($r, [
                    'game_id' => 'content_id',
                ]);

                $r['content_type'] = 'game';

                if ($r['rating'] > 3) {
                    $r['rating'] = 1;
                }
                elseif ($r['rating'] == 1) {
                    $r['rating'] = 0;
                }
                else {
                    continue;
                }

                $r['date'] = time();

                $rating = new Rating();
                $rating->fromArray($r, true);

                $ratingsProcessed[] = $rating;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Ratings Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('LikeDislike:Ratings')->insert($ratingsProcessed);
        }
        elseif ($stage == 'messages') {
            $messagesProcessed = [];
            $messages = $this->getQB('messages')->limit($importPerRun)->offset($offset)->get();

            foreach ($messages as $m) {
                $this->renameFields($m, [
                    'title' => 'subject',
                    'message' => 'body',
                    'user_id' => 'recipient_id'
                ]);

                $m['date'] = (new \DateTime($m['date']))->getTimestamp();

                $message = new PrivateMessage();
                $message->fromArray($m, true);

                $messagesProcessed[] = $message;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Private Messages Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('PrivateMessages')->insert($messagesProcessed);
        }
        elseif ($stage == 'friends') {
            $friendsProcessed = [];
            $friends = $this->getQB('friends')->limit($importPerRun)->offset($offset)->get();

            foreach ($friends as $m) {
                if (!$this->model('Friends')->friendshipExists($m['user1'], $m['user2'])) {
                    $friendship = new Friend();
                    $friendship->fromArray($m, true);

                    $friendsProcessed[] = $friendship;
                    $this->model('Friends')->insert($friendship);

                    $totalImported++;
                }
            }

            $message = ($offset + $totalImported).' Friends Imported (Run '.$run.')';
        }

        if ($totalImported == $importPerRun) {
            $run = $run + 1;
            $url = $this->generateUrl('ava_importer_run', ['stage' => $stage, 'run' => $run]);
        }
        else {
            $current = array_search($stage, $stages);
            if (isset($stages[$current + 1])) {
                $newStage = $stages[$current + 1];

                $url = $this->generateUrl('ava_importer_run', ['stage' => $newStage, 'run' => 1]);
            }
            else {
                $cacheClearer = new CacheClearer($this->getParam('cache_dir'));
                $cacheClearer->clearCaches();

                copy(__DIR__.'/../resources/ava-routes.yml', $this->getParam('root_dir').'/webmaster/config/routes.yml');

                return $this->redirect('home', [], 302, 'info', 'Import Complete');
            }
        }

        return new Response($this->render('@AvaImporter/admin/import_progress.twig', ['url' => $url, 'message' => $message]));
    }

    public function fixPointsAction()
    {
        $users = $this->getQB('users')->where('points', '>', 0)->get();

        foreach ($users as $u) {
            $user = $this->model('Users')->getOne($u['id']);
            $user->setId($u['id']);

            $curPoints = $user->points->getPoints();
            $user->points->setPoints($curPoints + $u['points']);
            $this->model('Users')->update($user);
        }

        return $this->redirect('home', [], 302, 'info', 'Points imported');
    }

    public function fixLikesAction()
    {
        $games = $this->model('Games');
        $ratings = $this->model('LikeDislike:Ratings');

        foreach ($games->query()->select(['id'])->get() as $game) {
            $likes = $ratings->query()->where('content_id', $game->getId())->where('content_type', 'game')->where('rating', '1')->count();

            $game->setLikes($likes);

            $games->update($game);
        }

        return $this->redirect('home', [], 302, 'info', 'Likes updated');
    }

    protected function renameFields(&$row, $renames)
    {
        foreach ($row as $fieldName => $fieldVal) {
            if (isset($renames[$fieldName])) {
                $row[$renames[$fieldName]] = $fieldVal;
                unset($row[$fieldName]);
            }
        }
    }

    protected function unencodeFields(&$row, $fields)
    {
        foreach ($fields as $field) {
            $row[$field] = htmlspecialchars_decode($row[$field]);
        }
    }

    /**
     * @return \Pixie\Connection
     * @throws \Exception
     */
    protected function getDatabaseConnection()
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        $session = $this->container->get('session');

        if (!$session->has('database_host')) {
            throw new \Exception('No database details in the session');
        }

        $config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => $session->get('database_host'),
            'database'  => $session->get('database_database'),
            'username'  => $session->get('database_username'),
            'password'  => $session->get('database_password'),
            'prefix'    => 'ava_', // Table prefix, optional
        );

        return $this->connection = new \Pixie\Connection('mysql', $config, 'QB');
    }

    /**
     * @param $table
     * @return \Pixie\QueryBuilder\QueryBuilderHandler
     */
    protected function getQB($table)
    {
        $qb = $this->getDatabaseConnection()->getQueryBuilder()->table($table);
        $qb->setFetchMode(\PDO::FETCH_ASSOC);

        return $qb;
    }
}
