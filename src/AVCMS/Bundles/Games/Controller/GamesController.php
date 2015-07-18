<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Games\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Games\Form\GameFrontendFiltersForm;
use AVCMS\Bundles\Games\Form\SubmitGameForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssFeed;
use AVCMS\Core\Rss\RssItem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GamesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Games\Model\Games
     */
    private $games;

    private $gameCategories;

    public function setUp() {
        $this->games = $this->model('Games');
        $this->gameCategories = $this->model('GameCategories');
    }

    public function playGameAction(Request $request, $slug)
    {
        $game = $this->games->find()
            ->published()
            ->slug($slug)
            ->join($this->gameCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$game) {
            throw $this->createNotFoundException('Game Not Found');
        }

        if ($game->getSubmitterId()) {
            $game->submitter = $this->model('@users')->getOne($game->getSubmitterId());
        }

        $hitRegistered = $this->container->get('hitcounter')->registerHit($this->games, $game->getId(), 'hits', 'id', 'last_hit');

        $playsLeft = null;
        if ($hitRegistered && $this->setting('games_limit_plays') && !$this->userLoggedIn()) {
            $played = $request->cookies->get('avcms_games_played', 0);
            $playsLeft = $this->setting('games_limit_plays') - $played;

            if ($playsLeft <= 0) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to continue playing games'));
            }
            else {
                $playCookie = new Cookie('avcms_games_played', $played + 1, time() + 1209600);
                $playsLeft--;
            }
        }

        $response = new Response($this->render('@Games/play_game.twig', ['game' => $game, 'plays_left' => $playsLeft]));
        if (isset($playCookie)) {
            $response->headers->setCookie($playCookie);
        }

        return $response;
    }

    public function browseGamesAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->games->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_games_per_page'))
            ->setSearchFields(['games.name'])
            ->handleRequest($request, [
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
                'mobile_only' => false
            ]);

        if ($this->setting('show_game_category')) {
            $query->join($this->gameCategories, ['name', 'slug']);
        }

        $category = null;
        if ($request->get('category') !== null) {
            $category = $this->gameCategories->getFullCategory($request->get('category'));

            if ($category) {
                $query->category($category);
            }
            else {
                throw $this->createNotFoundException('Category Not Found');
            }
        }

        if ($pageType == 'likes') {
            if ($request->get('likes_user') === null) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new AccessDeniedException('You must be logged in to view your liked games');
                }
            }
            else {
                $user = $this->model('Users')->find()->slug($request->get('likes_user'))->first();

                if (!$user) {
                    throw $this->createNotFoundException();
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'game');
            $query = $query->ids($ids, 'games.id');
        }

        if ($pageType === 'featured') {
            $query->featured();
        }

        $games = $query->get();

        $formBp = new GameFrontendFiltersForm();
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));

        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Games/browse_games.twig', array(
            'games' => $games,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'filters_form' => $filtersForm->createView(),
            'finder_request' => $finder->getRequestFilters(),
            'admin_settings' => $this->get('settings_manager'),
            'likes_user' => isset($user) ? $user : null,
        )));
    }

    public function submitGameAction(Request $request)
    {
        if (!$this->isGranted(['PERM_SUBMIT_GAMES'])) {
            if (!$this->isGranted(['IS_AUTHENTICATED_FULLY', 'IS_AUTHENTICATED_REMEMBERED'])) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to submit a game'));
            }
            else {
                throw new AccessDeniedException;
            }
        }

        if (!$this->setting('games_allow_uploads')) {
            throw $this->createNotFoundException();
        }

        $submissions = $this->model('GameSubmissions');

        $pendingCount = $submissions->query()->where('submitter_id', $this->activeUser()->getId())->count();
        if ($pendingCount >= $this->setting('games_max_submissions')) {
            return $this->redirect('home', [], 302, 'info', $this->trans('You have already submitted {count} games, please wait for them to be accepted first', ['count' => $this->setting('games_max_submissions')]));
        }

        $newSubmission = $submissions->newEntity();

        $formBlueprint = new SubmitGameForm(new CategoryChoicesProvider($this->model('GameCategories')));

        $form = $this->buildForm($formBlueprint, $request, $newSubmission);

        if ($form->isValid()) {
            $form->saveToEntities();

            $fileHandler = new UploadedFileHandler();

            $submissionsGamesDir = $this->getParam('games_dir').'/submissions';
            if (!file_exists($submissionsGamesDir)) {
                mkdir($submissionsGamesDir, 0777, true);
            }

            $submissionsThumbsDir = $this->getParam('game_thumbnails_dir').'/submissions';
            if (!file_exists($submissionsThumbsDir)) {
                mkdir($submissionsThumbsDir, 0777, true);
            }

            $fullFilePath = $fileHandler->moveFile($form->getData('file'), $submissionsGamesDir);
            $fileRelPath = str_replace($this->getParam('games_dir').'/', '', $fullFilePath);
            $newSubmission->setFile($fileRelPath);

            $fullThumbPath = $fileHandler->moveFile($form->getData('thumbnail'), $submissionsThumbsDir);
            $thumbRelPath = str_replace($this->getParam('game_thumbnails_dir').'/', '', $fullThumbPath);
            $newSubmission->setThumbnail($thumbRelPath);

            $newSubmission->setCreatorId($this->activeUser()->getId());
            $newSubmission->setSubmitterId($this->activeUser()->getId());
            $newSubmission->setDateAdded(time());

            $dimensions = @getimagesize($fullFilePath);

            if (!$dimensions) {
                $dimensions = [0, 0];
            }

            $newSubmission->setWidth($dimensions[0]);
            $newSubmission->setHeight($dimensions[1]);

            $newSubmission->setSlug($this->get('slug.generator')->slugify($newSubmission->getName()));

            $submissions->save($newSubmission);

        }

        return new Response($this->render('@Games/submit_game.twig', ['form' => $form->createView()]));
    }

    public function gamesRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Games\Model\Game[] $games
         */
        $games = $this->games->find()->published()->limit(30)->order('publish-date-newest')->get();

        $feed = new RssFeed(
            $this->trans('Games'),
            $this->generateUrl('browse_games', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->trans('The latest games')
        );

        foreach ($games as $game) {
            $url = $this->generateUrl('play_game', ['slug' => $game->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            $date = new \DateTime();
            $date->setTimestamp($game->getPublishDate());

            $feed->addItem(new RssItem($game->getName(), $url, $date, $game->getDescription()));
        }

        return new Response($feed->build(), 200, ['Content-Type' => 'application/xml']);
    }
}
