<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Wallpapers\Form\WallpapersAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class WallpapersAdminController extends AdminBaseController
{
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
        $formBlueprint = new WallpaperAdminForm();

        return $this->handleEdit($request, $this->wallpapers, $formBlueprint, 'wallpapers_admin_edit', '@Wallpapers/edit_wallpaper.twig', '@Wallpapers/wallpapers_browser.twig', array());
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

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new WallpapersAdminFiltersForm())->createView();

        return $template_vars;
    }
} 