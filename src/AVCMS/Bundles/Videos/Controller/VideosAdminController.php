<?php

namespace AVCMS\Bundles\Videos\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Categories\Controller\CategoryActionsTrait;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Videos\Form\ImportVideoAdminForm;
use AVCMS\Bundles\Videos\Form\VideoAdminForm;
use AVCMS\Bundles\Videos\Form\VideosAdminFiltersForm;
use AVCMS\Bundles\Videos\Form\VideosCategoryAdminForm;
use AVCMS\Bundles\Videos\Type\VideoManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class VideosAdminController extends AdminBaseController
{
    use CategoryActionsTrait;

    /**
     * @var \AVCMS\Bundles\Videos\Model\Videos
     */
    protected $videos;

    protected $browserTemplate = '@Videos/admin/videos_browser.twig';

    /**
     * @var VideoManager
     */
    private $videoManager;

    public function setUp()
    {
        $this->videos = $this->model('Videos');

        if (!$this->isGranted('ADMIN_VIDEOS')) {
            throw new AccessDeniedException;
        }

        $this->videoManager = $this->get('video_manager');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Videos/admin/videos_browser.twig');
    }

    public function editAction(Request $request)
    {
        $video = $this->videos->getOneOrNew($request->get('id', 0));

        if (!$video) {
            throw $this->createNotFoundException();
        }

        $formBlueprint = new VideoAdminForm($video, new CategoryChoicesProvider($this->model('VideoCategories')));

        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($this->videos, $form, $video);

        $helper->handleRequest($request);

        if ($helper->formValid()) {
            $helper->saveToEntities();

            $video = $helper->getEntity();
            $fileType = $request->request->get('video_file')['file_type'];
            if ($fileType === 'embed_code') {
                $video->setFile(null);
                $video->setProvider('embed');
            }
            else {
                $video->setEmbedCode('');

                if (!$request->request->get('provider') || $fileType !== 'file') {
                    $video->setProvider('default');
                }
            }

            $helper->save(false);
        }

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException('Video not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        $videoProvider = $this->videoManager->getType($video->getProvider());

        $allProviderNames = [];

        foreach ($this->videoManager->getTypes() as $type) {
            $allProviderNames[] = $type->getName();
        }

        return $this->createEditResponse(
            $helper,
            '@Videos/admin/edit_video.twig',
            ['videos_admin_edit', ['id' => $id]],
            ['provider' => $videoProvider, 'provider_names' => $allProviderNames,]
        );
    }

    public function finderAction(Request $request)
    {
        $finder = $this->videos->find()
            ->setSearchFields(array('videos.name'))
            ->setResultsPerPage(15)
            ->join($this->model('VideoCategories'), ['id', 'name', 'slug'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null, 'category' => 0));
        $items = $finder->get();

        return new Response($this->render('@Videos/admin/videos_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->videos);
    }

    public function toggleFeaturedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->videos, 'featured');
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->videos);
    }

    public function getVideoInfoAction(Request $request)
    {
        $url = $request->request->get('url');

        try {
            $importer = $this->videoManager->getImporterForUrl($url);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => true, 'provider' => ['name' => 'None', 'id' => 'none']]);
        }

        $video = $this->videos->newEntity();

        $importer->getVideoAtUrl($url, $video);

        $videoJson = $video->toArray();
        if (isset($videoJson['name'])) {
            $videoJson['slug'] = $this->get('slug.generator')->slugify($videoJson['name']);
        }

        return new JsonResponse([
            'success' => true,
            'video' => $videoJson,
            'provider' => [
                'name' => $importer->getName(),
                'id' => $importer->getId(),
            ]
        ]);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new VideosAdminFiltersForm(new CategoryChoicesProvider($this->model('VideoCategories'), true)))->createView();

        return $templateVars;
    }

    /**
     * @param $itemId
     * @return FormBlueprint
     */
    protected function getCategoryForm($itemId)
    {
        return new VideosCategoryAdminForm($itemId, $this->model('VideoCategories'));
    }
}
