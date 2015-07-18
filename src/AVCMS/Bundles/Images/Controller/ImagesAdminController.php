<?php

namespace AVCMS\Bundles\Images\Controller;

use AV\Cache\CacheClearer;
use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Categories\Controller\CategoryActionsTrait;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Form\ImageFilesAdminForm;
use AVCMS\Bundles\Images\Form\ImportImageAdminForm;
use AVCMS\Bundles\Images\Form\ImageAdminForm;
use AVCMS\Bundles\Images\Form\ImagesAdminFiltersForm;
use AVCMS\Bundles\Images\Form\ImagesCategoryAdminForm;
use AVCMS\Bundles\Images\Model\ImageFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ImagesAdminController extends AdminBaseController
{
    use CategoryActionsTrait;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageCollections
     */
    protected $images;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageFiles
     */
    protected $imageFiles;

    protected $browserTemplate = '@Images/admin/images_browser.twig';

    public function setUp()
    {
        $this->images = $this->model('ImageCollections');
        $this->imageFiles = $this->model('ImageFiles');

        if (!$this->isGranted('ADMIN_IMAGES')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Images/admin/images_browser.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     **/
    public function editAction(Request $request)
    {
        $image = $this->images->getOneOrNew($request->get('id', 0));

        if (!$image) {
            throw $this->createNotFoundException();
        }

        /**
         * @var $files \AVCMS\Bundles\Images\Model\ImageFile[]
         */
        $files = $this->imageFiles->getImageFiles($image->getId());

        $formBlueprint = new ImageAdminForm($image, new CategoryChoicesProvider($this->model('ImageCategories')));

        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($this->images, $form, $image);

        $helper->handleRequest($request);

        $filesForm = $this->buildForm(new ImageFilesAdminForm($files));

        foreach ($files as $file) {
            $filesForm->mergeData(['images' => [$file->getId() => ['file' => $file->getUrl(), 'caption' => $file->getCaption()]]]);
        }

        if ($form->isSubmitted()) {
            if (count($request->request->get('images')) === 1) {
                $helper->getForm()->addCustomErrors([new FormError(null, 'You must add at least one image file', true)]);
            }
        }

        if ($helper->formValid()) {
            $helper->saveToEntities();

            if ($image->getId() === null) {
                $helper->save(false);
            }

            $this->imageFiles->query()->where('collection_id', $image->getId())->delete();

            $imageFiles = [];
            foreach ($request->get('images') as $id => $file) {
                if ($id == 'new-files') {
                    continue;
                }

                if (!$file['file']) {
                    continue;
                }

                $imageFile = $this->imageFiles->newEntity();
                $imageFile->setUrl($file['file']);
                $imageFile->setCollectionId($image->getId());
                $imageFile->setCaption($file['caption']);
                $imageFiles[] = $imageFile;
                $this->imageFiles->save($imageFile);
            }

            $extension = pathinfo($imageFiles[0]->getUrl())['extension'];
            $image->setThumbnail($imageFiles[0]->getId().'.'.$extension);
            $image->setTotalImages(count($imageFiles));
            $helper->save(false);

            $this->get('cache.clearer')->clearCaches(['image-zips/'.$image->getId()]);

            $thumbnailsCacheClearer = new CacheClearer($this->getParam('images_dir'));
            $thumbnailsCacheClearer->clearCaches([$image->getId()]);
        }

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException('Image not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        return $this->createEditResponse(
            $helper,
            '@Images/admin/edit_image_collection.twig',
            ['images_admin_edit', ['id' => $id]],
            ['files' => $files, 'files_form' => $filesForm->createView()]
        );
    }

    public function finderAction(Request $request)
    {
        $finder = $this->images->find()
            ->setSearchFields(array('image_collections.name'))
            ->setResultsPerPage(15)
            ->join($this->model('ImageCategories'), ['id', 'name', 'slug'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null, 'category' => 0));
        $items = $finder->get();

        return new Response($this->render('@Images/admin/images_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->images);
    }

    public function toggleFeaturedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->images, 'featured');
    }

    public function deleteAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $ids = $request->request->get('ids');

        $this->imageFiles->query()->whereIn('collection_id', (array) $ids)->delete();

        return $this->handleDelete($request, $this->images);
    }

    public function clearThumbnailCacheAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $clearer = new CacheClearer($this->getParam('root_dir').'/'.$this->getParam('image_thumbnails_dir'));

        $clearer->clearCaches(null, true);

        return new JsonResponse(['success' => true]);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new ImagesAdminFiltersForm(new CategoryChoicesProvider($this->model('ImageCategories'), true)))->createView();

        return $templateVars;
    }

    /**
     * @param $itemId
     * @return FormBlueprint
     */
    protected function getCategoryForm($itemId)
    {
        return new ImagesCategoryAdminForm($itemId, $this->model('ImageCategories'));
    }
}
