<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AV\Cache\CacheClearer;
use AV\FileHandler\CurlFileHandler;
use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Categories\Controller\CategoryActionsTrait;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpapersAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Curl\Curl;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class WallpapersAdminController extends AdminBaseController
{
    use CategoryActionsTrait;

    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    protected $wallpapers;

    protected $browserTemplate = '@Wallpapers/admin/wallpapers_browser.twig';

    public function setUp(Request $request)
    {
        $this->wallpapers = $this->model('Wallpapers');

        if (!$this->isGranted('ADMIN_WALLPAPERS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, '@Wallpapers/admin/wallpapers_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new WallpaperAdminForm($request->get('id', 0), new CategoryChoicesProvider($this->model('WallpaperCategories')));

        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($this->wallpapers, $form);

        $helper->handleRequest($request);

        if ($helper->formValid()) {
            $imagePath = $this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir.'/'.$helper->getForm()->getData('file');

            if (!file_exists($imagePath)) {
                $form->addCustomErrors([new FormError('file', 'File does not exist: '.$imagePath)]);
            }
            else {
                $size = @getimagesize($imagePath);

                if (!is_array($size)) {
                    $form->addCustomErrors([new FormError('file', 'Could not get image dimension for file: '.$imagePath)]);
                }

                $helper->getEntity()->setOriginalWidth($size[0]);
                $helper->getEntity()->setOriginalHeight($size[1]);
                $helper->save();
            }
        }

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException(ucwords(str_replace('_', ' ', $this->wallpapers->getSingular())).' not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        if ($helper->formValid()) {
            $cacheClearer = new CacheClearer($this->container->getParameter('root_dir').'/'.$this->container->getParameter('web_path').'/'.$this->bundle->config->web_dir);
            $cacheClearer->clearCaches([$helper->getEntity()->getId()]);
        }

        return $this->createEditResponse(
            $helper,
            $request,
            '@Wallpapers/admin/edit_wallpaper.twig',
            '@Wallpapers/admin/wallpapers_browser.twig',
            array('wallpapers_admin_edit', array('id' => $id)),
            []
        );
    }

    public function finderAction(Request $request)
    {
        $finder = $this->wallpapers->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('WallpaperCategories'), ['id', 'name', 'slug'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Wallpapers/admin/wallpapers_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->wallpapers);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->wallpapers);
    }

    public function findFilesAction(Request $request)
    {
        $q = $request->get('q');

        $dir = $this->bundle->config->wallpapers_dir;

        $itr = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        $data = [];

        foreach ($itr as $file) {
            if ($file->isFile() && strpos($file->getFilename(), $q) !== false) {
                $relativeDir = str_replace($dir, '', $file->getPath().'/'.$file->getFilename());
                $relativeDir = ($relativeDir[0] === '/' ? substr($relativeDir, 1) : $relativeDir);
                $relativePretty = str_replace('/', ' » ', $relativeDir);
                $data[] = ['id' => $relativeDir, 'text' => $relativePretty];
            }
        }

        return new JsonResponse($data);
    }

    public function uploadFilesAction(Request $request)
    {
        /**
         * @var $file UploadedFile
         */
        if ($request->files->has('file')) {
            $file = $request->files->get('file');
        }
        else {
            $file = $request->files->get($request->query->get('type'), null)['upload'];
        }

        $wpDir = $this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir.'/';

        if ($request->request->has('folder')) {
            $path = $wpDir.str_replace('.', '', $request->request->get('folder'));
        }
        else {
            $path = $wpDir.$file->getClientOriginalName()[0];
        }

        $handler = new UploadedFileHandler(UploadedFileHandler::getImageFiletypes());

        if (($fullPath = $handler->moveFile($file, $path, null, $request->request->get('existing_files', UploadedFileHandler::EXISTS_NUMBER))) === false) {
            $fileJson = ['success' => false, 'error' => $handler->getTranslatedError($this->translator)];
        }
        else {
            $fileJson = ['success' => true, 'file' => $file->getClientOriginalName()[0].'/'.basename($fullPath)];
        }

        return new JsonResponse($fileJson);
    }

    public function grabFileAction(Request $request)
    {
        $fileUrl = $request->get('file_url');

        if (!$fileUrl) {
            return new JsonResponse(['success' => false, 'error' => $this->trans('No URL Entered')]);
        }

        $curl = new Curl();

        $file = $curl->get($fileUrl);

        $handler = new CurlFileHandler(CurlFileHandler::getImageFiletypes());

        $path = $this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir.'/'.basename($fileUrl)[0];

        if (($fullPath = $handler->moveFile($fileUrl, $file, $path)) === false) {
            $fileJson = ['success' => false, 'error' => $handler->getTranslatedError($this->translator)];
        }
        else {
            $fileJson = ['success' => true, 'file' => basename($fileUrl)[0].'/'.basename($fullPath)];
        }

        return new JsonResponse($fileJson);
    }

    public function editResolutionsAction(Request $request)
    {
        $rm = $this->container->get('wallpaper.resolutions_manager');
        $config = $rm->getResolutionsConfig();

        $formBlueprint = new FormBlueprint();
        $formBlueprint->add('config', 'textarea', [
            'label' => 'Wallpaper Resolutions Config',
            'default' => $config,
            'attr' => [
                'rows' => 20,
                'class' => 'monospaced-field'
            ]
        ]);
        $formBlueprint->setSuccessMessage('Resolution Config Saved');

        $form = $this->buildForm($formBlueprint, $request);

        if ($form->isSubmitted()) {
            $config = $form->getData('config');
            try {
                $rm->saveResolutionsConfig($config);
            }
            catch (\Exception $e) {
                $form->addCustomErrors([new FormError('config', $e->getMessage())]);
            }

            return new JsonResponse(['success' => false, 'form' => $form->createView()->getJsonResponseData()]);
        }

        return new Response($this->renderAdminSection('@Wallpapers/admin/manage_resolutions.twig', $request->get('ajax_depth'), [
            'form' => $form->createView()

        ]));
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new WallpapersAdminFiltersForm())->createView();

        return $template_vars;
    }
} 
