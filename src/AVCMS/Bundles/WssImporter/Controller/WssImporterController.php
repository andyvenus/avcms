<?php
/**
 * User: Andy
 * Date: 26/01/15
 * Time: 13:34
 */

namespace AVCMS\Bundles\WssImporter\Controller;

use AVCMS\Bundles\Adverts\Model\Advert;
use AVCMS\Bundles\Blog\Model\BlogPost;
use AVCMS\Bundles\Comments\Model\Comment;
use AVCMS\Bundles\Links\Model\Link;
use AVCMS\Bundles\Pages\Model\Page;
use AVCMS\Bundles\Tags\Model\Tag;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Bundles\Wallpapers\Model\Wallpaper;
use AVCMS\Bundles\Wallpapers\Model\WallpaperCategory;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WssImporterController extends Controller
{
    protected $connection;

    public function importerHomeAction(Request $request)
    {

    }

    public function doImportAction(Request $request)
    {

        $stages = ['wallpapers', 'wallpaper_categories', 'tags', 'tag_relations', 'users', 'wallpaper_comments', 'news', 'news_comments', 'pages', 'links', 'adverts'];

        $importPerRun = 1000;
        $stage = $request->get('stage', $stages[0]);
        $run = $request->get('run', 1);

        $offset = $run * $importPerRun - $importPerRun;

        $totalImported = 0;

        $message = null;

        if ($stage === 'wallpapers') {
            // WALLPAPERS //
            $wallpapers = $this->getQB('wallpapers')->limit($importPerRun)->offset($offset)->get();

            $wallpapersProcessed = [];
            foreach ($wallpapers as $wp) {
                $this->renameFields($wp, [
                    'category' => 'category_id',
                    'category_parent' => 'category_parent_id',
                    'seo_url' => 'slug',
                    'downloads' => 'total_downloads',
                    'display' => 'resize_type',
                    'submitter' => 'submitter_id'
                ]);

                //$wp['file'] = preg_replace('/files\//', '', $wp['file'], 1);
                $wp['creator_id'] = $this->activeUser()->getId();

                $wp['publish_date'] = ($wp['date_added'] ? $wp['date_added'] : time());

                $wp['crop_position'] = str_replace('middle-', '', $wp['valign'] . '-' . $wp['align']);

                $wallpaper = new Wallpaper();
                $wallpaper->fromArray($wp, true);

                $wallpapersProcessed[] = $wallpaper;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Wallpapers Imported (Run '.$run.')';

            if ($totalImported) {
                $this->model('AVCMS\Bundles\Wallpapers\Model\Wallpapers')->insert($wallpapersProcessed);
            }
        }
        elseif ($stage === 'wallpaper_categories') {
            // WALLPAPER CATEGORIES //
            $categories = $this->getQB('cats')->limit($importPerRun)->offset($offset)->get();

            $catsProcessed = [];
            foreach ($categories as $cat) {
                $this->renameFields($cat, [
                    'cat_order' => 'order',
                    'seo_url' => 'slug',
                    'parent_id' => 'parent',
                ]);

                $this->unencodeFields($cat, ['name', 'description']);

                $nuCat = new WallpaperCategory();
                $nuCat->fromArray($cat, true);

                $catsProcessed[] = $nuCat;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Categories Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('AVCMS\Bundles\Wallpapers\Model\WallpaperCategories')->insert($catsProcessed);
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
                $this->model('AVCMS\Bundles\Tags\Model\TagsModel')->insert($tagsProcessed);
        }
        elseif ($stage === 'tag_relations') {
            // TAG RELATIONS //
            $relations = $this->getQB('tag_relations')->limit($importPerRun)->offset($offset)->get();

            foreach ($relations as $r) {
                $this->renameFields($r, [
                    'tag_id' => 'taxonomy_id',
                    'wallpaper_id' => 'content_id'
                ]);

                $r['content_type'] = 'wallpaper';

                unset($r['id']);

                $this->model('AVCMS\Bundles\Tags\Model\TagsTaxonomyModel')->query()->insert($r);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Tag relations Imported (Run '.$run.')';
        }
        elseif ($stage == 'users') {
            $usersProcessed = [];
            $users = $this->getQB('users')->limit($importPerRun)->offset($offset)->get();

            foreach ($users as $u) {
                if ($u['id'] == 1) {
                    continue;
                }

                $this->renameFields($u, [
                    'seo_url' => 'slug',
                ]);

                if ($u['password_bcrypt']) {
                    $u['password'] = $u['password_bcrypt'];
                }

                if ($u['admin'] == 1) {
                    $u['role_list'] = 'ROLE_SUPER_ADMIN';
                }

                $newUser = new User();
                $newUser->fromArray($u, true);

                $usersProcessed[] = $newUser;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Users Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('AVCMS\Bundles\Users\Model\Users')->insert($usersProcessed);
        }
        elseif ($stage == 'wallpaper_comments') {
            $commentsProcessed = [];
            $comments = $this->getQB('comments')->limit($importPerRun)->offset($offset)->get();

            foreach ($comments as $c) {
                $this->renameFields($c, ['link_id' => 'content_id', 'user' => 'user_id']);
                $this->unencodeFields($c, ['comment']);
                $c['content_type'] = 'wallpaper';

                $wallpapers = $this->model('AVCMS\Bundles\Wallpapers\Model\Wallpapers');
                $wallpaper = $wallpapers->query()->where('id', $c['content_id'])->select(['name', 'comments'])->first();
                if ($wallpaper) {
                    $c['content_title'] = $wallpaper->getName();
                }
                else {
                    $c['content_title'] = 'No longer exists';
                }

                unset($c['id']);

                $newComment = new Comment();
                $newComment->fromArray($c, true);

                $commentsProcessed[] = $newComment;

                $wallpapers->query()->where('id', $c['content_id'])->update(['comments' => $wallpapers->query()->raw('comments + 1')]);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Wallpaper Comments Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('AVCMS\Bundles\Comments\Model\Comments')->insert($commentsProcessed);
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

                $n['publish_date'] = ($n['date_added'] ? $n['date_added'] : time());

                $blogPost = new BlogPost();
                $blogPost->fromArray($n, true);

                $newsProcessed[] = $blogPost;

                $totalImported++;
            }

            $message = ($offset + $totalImported).' Blog Posts Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('AVCMS\Bundles\Blog\Model\BlogPosts')->insert($newsProcessed);
        }
        elseif ($stage == 'news_comments') {
            $commentsProcessed = [];
            $comments = $this->getQB('news_comments')->limit($importPerRun)->offset($offset)->get();

            foreach ($comments as $c) {
                $this->renameFields($c, ['link_id' => 'content_id', 'user' => 'user_id']);
                $this->unencodeFields($c, ['comment']);
                $c['content_type'] = 'blog_post';

                $blogPosts = $this->model('AVCMS\Bundles\Blog\Model\BlogPosts');
                $wallpaper = $blogPosts->query()->where('id', $c['content_id'])->select(['title', 'comments'])->first();
                if ($wallpaper) {
                    $c['content_title'] = $wallpaper->getTitle();
                }
                else {
                    $c['content_title'] = 'No longer exists';
                }

                unset($c['id']);

                $newComment = new Comment();
                $newComment->fromArray($c, true);

                $commentsProcessed[] = $newComment;

                $blogPosts->query()->where('id', $c['content_id'])->update(['comments' => $blogPosts->query()->raw('comments + 1')]);

                $totalImported++;
            }

            $message = ($offset + $totalImported).' News Comments Imported (Run '.$run.')';

            if ($totalImported)
                $this->model('AVCMS\Bundles\Comments\Model\Comments')->insert($commentsProcessed);
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
                $this->model('AVCMS\Bundles\Pages\Model\Pages')->insert($pagesProcessed);
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
                $this->model('AVCMS\Bundles\Links\Model\Links')->insert($linksProcessed);
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
                $this->model('AVCMS\Bundles\Adverts\Model\Adverts')->insert($advertsProcessed);
        }

        if ($totalImported == $importPerRun) {
            $run = $run + 1;
            $url = $this->generateUrl('wss_importer_run', ['stage' => $stage, 'run' => $run]);
        }
        else {
            $current = array_search($stage, $stages);
            if (isset($stages[$current + 1])) {
                $newStage = $stages[$current + 1];

                $url = $this->generateUrl('wss_importer_run', ['stage' => $newStage, 'run' => 1]);
            }
            else {
                return new Response('done');
            }
        }

        return new Response('<html><head><meta http-equiv="refresh" content="1; url='.$url.'" /></head>'.$message.'</html>');
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
     */
    protected function getDatabaseConnection()
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        $config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => 'wss',
            'username'  => 'root',
            'password'  => 'root',
            'prefix'    => 'wss_', // Table prefix, optional
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
