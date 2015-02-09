<?php

namespace AVCMS\Bundles\Games\Controller;

use AV\FileHandler\CurlFileHandler;
use AV\FileHandler\FileHandlerBase;
use AV\Form\FormBlueprint;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Games\Form\FeedGamesAdminFiltersForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Curl\Curl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FeedGamesAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Games\Model\FeedGames
     */
    protected $feedGames;

    /**
     * @var \AVCMS\Bundles\Games\Model\Games
     */
    protected $games;

    protected $browserTemplate = '@Games/admin/feed_games_browser.twig';

    public function setUp()
    {
        $this->feedGames = $this->model('FeedGames');
        $this->games = $this->model('Games');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Games/admin/feed_games_browser.twig');
    }

    public function updateFeedsAction()
    {
        $feeds = $this->get('game_feed_downloader')->getFeeds();

        $feedInfoArray = [];
        foreach ($feeds as $feed) {
            $feedInfo = $feed->getInfo();
            $feedInfo['id'] = $feed->getId();

            $feedInfoArray[] = $feedInfo;
        }

        return new Response($this->renderAdminSection('@Games/admin/update_feeds.twig', ['feeds' => $feedInfoArray]));
    }

    public function downloadFeedAction()
    {
        $games = $this->container->get('game_feed_downloader')->downloadFeed('flash_game_distribution');

        return new JsonResponse(['success' => true, 'new_games' => count($games), 'message' => $this->trans('{count} games added', ['count' => count($games)])]);
    }

    public function importGameAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            throw new AccessDeniedException;
        }

        $id = $request->get('id');
        $category = $request->get('category');

        $feedGame = $this->feedGames->getOne($id);

        if (!$feedGame) {
            return new JsonResponse(['success' => false, 'error' => 'Game Not Found']);
        }

        $game = $this->games->newEntity();
        $game->fromArray($feedGame->toArray(), true);

        $game->setCategoryId($category);
        $game->setId(null);

        if ($this->setting('download_feed_games')) {
            $curl = new Curl();

            try {
                // Game File
                $file = $curl->get($game->getFile());

                $handler = new CurlFileHandler(null, ['php' => '*']);

                $filePath = $handler->moveFile($game->getFile(), $file, $this->getParam('games_dir').'/'.basename($game->getFile())[0]);

                if ($filePath === false) {
                    return new JsonResponse(['success' => false, 'error' => $handler->getTranslatedError($this->translator)]);
                }

                // Thumbnail
                $file = $curl->get($game->getThumbnail());

                $handler = new CurlFileHandler(FileHandlerBase::getImageFiletypes());

                $thumbnailPath = $handler->moveFile($game->getThumbnail(), $file, $this->getParam('game_thumbnails_dir').'/'.basename($game->getFile())[0]);

                if ($thumbnailPath === false) {
                    return new JsonResponse(['success' => false, 'error' => $handler->getTranslatedError($this->translator)]);
                }

            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
            }

            $game->setFile(str_replace($this->getParam('games_dir').'/', '', $filePath));
            $game->setThumbnail(str_replace($this->getParam('game_thumbnails_dir').'/', '', $thumbnailPath));
        }

        $slug = $this->container->get('slug.generator')->slugify($game->getName());
        if ($this->games->query()->where('slug', $slug)->count() > 0) {
            $slug .= '-'.time();
        }
        $game->setSlug($slug);
        $game->setPublished(1);
        $game->setPublishDate(time());

        $helper = $this->editContentHelper($this->games, null, $game);
        $helper->save();

        $feedGame->setStatus('imported');
        $this->feedGames->save($feedGame);

        return new JsonResponse(['success' => true, 'url' => $this->generateUrl('games_admin_edit', ['id' => $game->getId()])]);
    }

    public function playGameAction(Request $request)
    {
        $game = $this->feedGames->getOne($request->get('id'));

        if (!$game) {
            throw $this->createNotFoundException();
        }

        return new Response($this->renderAdminSection('@Games/admin/play_game_admin.twig', ['game' => $game]));
    }

    public function finderAction(Request $request)
    {
        $finder = $this->feedGames->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));

        /* @var $items \AVCMS\Bundles\Games\Model\FeedGame[] */
        $items = $finder->get();

        $categoryFields = new FormBlueprint();

        $categories = [0 => 'Default'] + (new CategoryChoicesProvider($this->model('GameCategories')))->getChoices();

        foreach ($items as $item) {
            $itemCategory = $item->getCategory();

            $default = null;
            foreach ($categories as $id => $categoryName) {
                if (strpos($itemCategory, $categoryName) !== false) {
                    $default = $id;
                    break;
                }
            }

            $categoryFields->add('category-'.$item->getId(), 'select', [
                'choices' => $categories,
                'default' => $default
            ]);
        }

        $categoryFieldsForm = $this->buildForm($categoryFields);

        $feeds = $this->get('game_feed_downloader')->getFeeds();

        return new Response($this->render(
            '@Games/admin/feed_games_finder.twig',
            [
                'items' => $items,
                'page' => $finder->getCurrentPage(),
                'feeds' => $feeds,
                'categories' => $categoryFieldsForm->createView()
            ]
        ));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->feedGames);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new FeedGamesAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
