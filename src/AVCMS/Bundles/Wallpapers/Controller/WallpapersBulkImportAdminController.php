<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\BulkUploadForm;
use AVCMS\Bundles\Wallpapers\Form\RecursiveDirectoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Wallpapers\Form\WallpapersBulkImportFiltersForm;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersBulkImportAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    protected $wallpapers;

    protected $browserTemplate = '@Wallpapers/admin/wallpaper_bulk_import_browser.twig';

    public function setUp(Request $request)
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Wallpapers/admin/wallpaper_bulk_import_browser.twig');
    }

    public function importAction(Request $request)
    {
        $folder = str_replace(['.', '@'], ['', '/'], $request->get('folder'));
        if (!$folder) {
            throw $this->createNotFoundException("Folder $folder not found");
        }

        $entity = $this->wallpapers->newEntity();

        $formBlueprint = new WallpaperAdminForm(0, new CategoryChoicesProvider($this->model('WallpaperCategories')), true);
        $formBlueprint->setSuccessMessage('Wallpapers Imported');
        $form = $this->buildForm($formBlueprint);

        $contentHelper = $this->editContentHelper($this->wallpapers, $form, $entity);
        $contentHelper->handleRequest($request);

        $itr = new \DirectoryIterator($this->bundle->config->wallpapers_dir.'/'.$folder);

        if ($contentHelper->formSubmitted()) {
            if ($contentHelper->formValid()) {
                $form->saveToEntities();

                $originalTags = $contentHelper->getForm()->getData('tags');
                $wallpapersImported = 0;

                foreach ($itr as $file) {
                    if (!$file->isFile() || exif_imagetype($file->getPathname()) === false) {
                        continue;
                    }

                    if ($this->wallpapers->query()->where('file', $folder.'/'.$file->getFilename())->count() > 0) {
                        continue;
                    }

                    $newWallpaper = clone $entity;
                    $contentHelper->setEntity($newWallpaper);
                    $newWallpaper->setFile($folder.'/'.$file->getFilename());

                    $dimensions = @getimagesize($file->getPath().'/'.$file->getFilename());
                    if (!is_array($dimensions)) {
                        $dimensions = [0, 0];
                    }

                    $replaceValues['{filename}'] = str_replace('.'.$file->getExtension(), '', $file->getBasename());
                    $replaceValues['{clean_filename}'] = ucwords(str_replace('_', ' ', str_replace('.'.$file->getExtension(), '', $file->getBasename())));
                    $replaceValues['{folder_name}'] = basename($folder);
                    $replaceValues['{original_width}'] = $dimensions[0];
                    $replaceValues['{original_height}'] = $dimensions[1];

                    $allData = $newWallpaper->toArray();
                    $newData = [];

                    foreach ($allData as $key => $value) {
                        if (!is_array($value)) {
                            $newData[$key] = str_replace(array_keys($replaceValues), $replaceValues, $value);
                        }
                    }

                    $newTags = str_replace(array_keys($replaceValues), $replaceValues, $originalTags);
                    $contentHelper->getForm()->setData('tags', $newTags);

                    $newWallpaper->fromArray($newData);

                    $slug = $this->container->get('slug.generator')->slugify($newWallpaper->getName());
                    if ($this->wallpapers->query()->where('slug', $slug)->count() > 0) {
                        $slug .= '-'.time();
                    }

                    $newWallpaper->setSlug($slug);
                    $newWallpaper->setOriginalWidth($dimensions[0]);
                    $newWallpaper->setOriginalHeight($dimensions[1]);

                    $contentHelper->save(false);
                    $wallpapersImported++;
                }

                $formBlueprint->setSuccessMessage("$wallpapersImported ".$this->trans("wallpapers imported"));
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        $totalFiles = 0;
        foreach ($itr as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['gif', 'bmp', 'jpeg', 'jpg', 'png'])) {
                $totalFiles++;
            }
        }

        $importedFiles = $this->wallpapers->query()->where('file', 'LIKE', $folder.'%')->count();

        return new Response($this->renderAdminSection('@Wallpapers/admin/wallpaper_bulk_import.twig', $request->get('ajax_depth'), [
            'item' => ['id' => $folder],
            'folder' => $folder,
            'form' => $form->createView(),
            'total_files' => $totalFiles,
            'imported_files' => $importedFiles
        ]));
    }

    public function finderAction(Request $request)
    {
        $showSingleCharDirs = $request->get('show_single_char_dirs', false);

        $dirs = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->bundle->config->wallpapers_dir),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $items = [];

        if ($request->get('page') == 1) {
            foreach ($dirs as $file) {
                if (in_array($file->getBasename(), array('.', '..'))) {
                    continue;
                } elseif ($file->isDir() && ($showSingleCharDirs == 1 || strlen($file->getFilename()) > 1)) {
                    $filename = str_replace($this->bundle->config->wallpapers_dir.'/', '', $file->getPath().'/'.$file->getFilename());
                    if (!$request->get('search') || strpos($filename, $request->get('search')) !== false) {
                        $id = str_replace('/', '@', $filename);

                        $fileItr = new \DirectoryIterator($this->bundle->config->wallpapers_dir.'/'.$filename);
                        $totalFiles = 0;
                        foreach ($fileItr as $innerFile) {
                            if ($innerFile->isFile() && in_array($innerFile->getExtension(), ['gif', 'bmp', 'jpeg', 'jpg', 'png'])) {
                                $totalFiles++;
                            }
                        }
                        $importedFiles = $this->wallpapers->query()->where('file', 'LIKE', $filename.'%')->count();

                        $items[] = ['id' => $id, 'name' => $filename, 'imported_files' => $importedFiles, 'total_files' => $totalFiles, 'dir' => $file];
                    }
                }
            }
        }

        return new Response($this->render('@Wallpapers/admin/wallpaper_bulk_import_finder.twig', array('items' => $items, 'page' => 1)));
    }

    public function bulkUploadAction(Request $request)
    {
        $form = $this->buildForm(new BulkUploadForm(new RecursiveDirectoryChoicesProvider($this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir, false)), $request);

        return new Response($this->renderAdminSection('@Wallpapers/admin/bulk_upload.twig', $request->get('ajax_depth'), ['form' => $form->createView()]));
    }

    public function addFolderAction(Request $request)
    {
        $formBp = new FormBlueprint();
        $formBp->setName('wallpaper_new_folder');
        $formBp->setAction($this->generateUrl('wallpapers_bulk_import_new_folder'));
        $formBp->add('folder_name', 'text', [
            'label' => 'Folder',
            'help' => 'The new folder directory, use forward slashes to create a sub-directory'
        ]);

        $form = $this->buildForm($formBp, $request);

        if ($form->isSubmitted()) {

            $newDir = $this->bundle->config->wallpapers_dir.'/'.$form->getData('folder_name');

            if (file_exists($newDir)) {
                $form->addCustomErrors([new FormError('folder_name', 'Folder already exists')]);
            }
            else {
                mkdir($newDir, 0777, true);
            }

            return new JsonResponse(['success' => $form->isValid(), 'form' => $form->createView()->getJsonResponseData(), 'folder' => $form->getData('folder_name')]);
        }

        return new JsonResponse(['html' => $this->render('@CmsFoundation/modal_form.twig', ['form' => $form->createView(), 'modal_title' => 'Add Folder'])]);
    }

    public function deleteFolderAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $folder = str_replace(['.', '@'], ['', '/'], $request->request->get('ids'));
        $fullPath = $this->container->getParameter('root_dir').'/'.$this->bundle->config->wallpapers_dir.'/'.$folder;
        if (!$folder || !file_exists($fullPath)) {
            return new JsonResponse(['success' => 0, 'error' => $this->trans('Folder cannot be deleted because it doesn\'t exist')]);
        }

        if (count(glob($fullPath."/*")) === 0) {
            rmdir($fullPath);
            return new JsonResponse(['success' => 1]);
        }
        else {
            return new JsonResponse(['success' => 0, 'error' => $this->trans('Folder not empty')]);
        }
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $fbp = new WallpapersBulkImportFiltersForm();
        $fbp->remove('order');

        $templateVars['finder_filters_form'] = $this->buildForm($fbp)->createView();

        return $templateVars;
    }
}
