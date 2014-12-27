<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AV\Cache\CacheClearer;
use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Categories\Controller\CategoryActionsTrait;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpapersAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class WallpapersAdminController extends AdminBaseController
{
    use CategoryActionsTrait;

    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    protected $wallpapers;

    public function setUp(Request $request)
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, '@Wallpapers/wallpapers_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new WallpaperAdminForm($request->get('id', 0), new CategoryChoicesProvider($this->model('WallpaperCategories')));

        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($this->wallpapers, $form);

        $helper->handleRequestAndSave($request);

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
            '@Wallpapers/edit_wallpaper.twig',
            '@Wallpapers/wallpapers_browser.twig',
            array('wallpapers_admin_edit', array('id' => $id)),
            []
        );
    }

    public function finderAction(Request $request)
    {
        $finder = $this->wallpapers->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Wallpapers/wallpapers_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
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
        $file = $request->files->get($request->query->get('type'), null)['upload'];

        $path = $this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir.'/'.$file->getClientOriginalName()[0];

        $handler = new UploadedFileHandler(UploadedFileHandler::getImageFiletypes());

        if (($fullPath = $handler->moveFile($file, $path)) === false) {
            $fileJson = ['success' => false, 'error' => $handler->getTranslatedError($this->translator)];
        }
        else {
            $fileJson = ['success' => true, 'file' => $file->getClientOriginalName()[0].'/'.basename($fullPath)];
        }

        return new JsonResponse($fileJson);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new WallpapersAdminFiltersForm())->createView();

        return $template_vars;
    }
} 
