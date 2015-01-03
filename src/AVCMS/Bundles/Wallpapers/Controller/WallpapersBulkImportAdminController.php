<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Admin\Event\AdminSaveContentEvent;
use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
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
        $folder = str_replace('.', '', $request->get('folder'));
        if (!$folder) {
            throw $this->createNotFoundException("Folder $folder not found");
        }

        $entity = $this->wallpapers->newEntity();

        $formBlueprint = new WallpaperAdminForm(0, new CategoryChoicesProvider($this->model('WallpaperCategories')), true);
        $formBlueprint->setSuccessMessage('Wallpapers Imported');
        $form = $this->buildForm($formBlueprint);

        $contentHelper = $this->editContentHelper($this->wallpapers, $form, $entity);
        $contentHelper->handleRequest($request);

        if ($contentHelper->formSubmitted()) {
            if ($contentHelper->formValid()) {
                $form->saveToEntities();



                $itr = new \DirectoryIterator($this->bundle->config->wallpapers_dir.'/'.$folder);
                foreach ($itr as $file) {
                    if ($file->isFile() && exif_imagetype($file->getPathname()) !== false) {
                        $newWallpaper = clone $entity;
                        $contentHelper->setEntity($newWallpaper);
                        $newWallpaper->setFile($folder.'/'.$file->getFilename());

                        $replaceValues['{filename}'] = str_replace('.'.$file->getExtension(), '', $file->getBasename());
                        $replaceValues['{clean_filename}'] = ucwords(str_replace('_', ' ', str_replace('.'.$file->getExtension(), '', $file->getBasename())));

                        $allData = $newWallpaper->toArray();
                        $newData = [];

                        foreach ($allData as $key => $value) {
                            if (!is_array($value)) {
                                $newData[$key] = str_replace(array_keys($replaceValues), $replaceValues, $value);
                            }
                        }

                        $newWallpaper->fromArray($newData);

                        $contentHelper->save(false);
                    }
                }
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        return new Response($this->renderAdminSection('@Wallpapers/admin/wallpaper_bulk_import.twig', $request->get('ajax_depth'), ['item' => ['id' => $folder], 'folder' => $folder, 'form' => $form->createView()]));
    }

    public function finderAction(Request $request)
    {
        $dirs = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->bundle->config->wallpapers_dir),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $items = [];

        if ($request->get('page') == 1) {
            foreach ($dirs as $file) {
                if (in_array($file->getBasename(), array('.', '..'))) {
                    continue;
                } elseif ($file->isDir()) {
                    if (!$request->get('search') || strpos($file->getFilename(), $request->get('search')) !== false)
                        $items[] = ['id' => $file->getFilename(), 'name' => $file->getFilename()];
                }
            }
        }

        usort($items, function($s1, $s2) {
            if (strlen($s1['name']) !== 1 && strlen($s2['name']) === 1) {
                return -1;
            } elseif (strlen($s2['name']) !== 1 && strlen($s1['name']) === 1) {
                return 1;
            } else {
                return 0;
            }
        });

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

        $fbp = new AdminFiltersForm();
        $fbp->remove('order');

        $templateVars['finder_filters_form'] = $this->buildForm($fbp)->createView();

        return $templateVars;
    }
}
