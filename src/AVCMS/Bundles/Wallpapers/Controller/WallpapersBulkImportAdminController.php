<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Admin\Event\AdminSaveContentEvent;
use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminForm;
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

                    $replaceValues['{filename}'] = str_replace('.'.$file->getExtension(), '', $file->getBasename());
                    $replaceValues['{clean_filename}'] = ucwords(str_replace('_', ' ', str_replace('.'.$file->getExtension(), '', $file->getBasename())));
                    $replaceValues['{folder_name}'] = basename($folder);

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
                    if (!$request->get('search') || strpos($file->getFilename(), $request->get('search')) !== false) {
                        $filename = str_replace($this->bundle->config->wallpapers_dir.'/', '', $file->getPath().'/'.$file->getFilename());
                        $id = str_replace('/', '@', $filename);
                        $items[] = ['id' => $id, 'name' => $filename];
                    }
                }
            }
        }

        return new Response($this->render('@Wallpapers/admin/wallpaper_bulk_import_finder.twig', array('items' => $items, 'page' => 1)));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->wallpapers);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->wallpapers);
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
