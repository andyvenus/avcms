<?php

namespace AVCMS\Bundles\Generated\Controller;

use AVCMS\Bundles\Generated\Form\AdvertsAdminFiltersForm;
use AVCMS\Bundles\Generated\Form\AdvertAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdvertsAdminController extends AdminController
{
    public function homeAction(Request $request)
    {
       return $this->manage($request, '@Generated/adverts_browser.twig');
    }

    public function editAction(Request $request)
    {
        $model = $this->model('Adverts');

        $form_blueprint = new AdvertAdminForm();

        return $this->edit($request, $model, $form_blueprint, 'adverts_admin_edit', '@Generated/edit_advert.twig', '@Generated/adverts_browser.twig', array('content_name' => 'Advert'));
    }

    public function finderAction(Request $request)
    {
        $model = $this->model('Adverts');

        $finder = $model->find()
            ->setSearchFields(array('adName'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Generated/adverts_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        $model = $this->model('Adverts');

        return $this->delete($request, $model);
    }

    public function togglePublishedAction(Request $request)
    {
        $model = $this->model('Adverts');

        return $this->togglePublished($request, $model);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new AdvertsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 