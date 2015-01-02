<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperBulkImportAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersBulkImportAdminController extends AdminBaseController
{
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
       return $this->handleManage($request, '@Wallpapers/admin/wallpaper_bulk_import_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new WallpaperBulkImportAdminForm();

        return $this->handleEdit($request, $this->wallpapers, $formBlueprint, 'wallpaper_bulk_import_admin_edit', '@Wallpapers/admin/edit_wallpaper_bulk_import.twig', '@Wallpapers/admin/wallpaper_bulk_import_browser.twig', array());
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
                        $items[] = ['name' => $file->getFilename()];
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
