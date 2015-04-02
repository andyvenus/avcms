<?php

namespace AVCMS\Bundles\TranslationHelper\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\TranslationHelper\Form\TranslationAdminForm;
use AVCMS\Bundles\TranslationHelper\Form\TranslationsAdminFiltersForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\TranslationHelper\Model\Translations
     */
    protected $translations;

    protected $browserTemplate = '@TranslationHelper/admin/translations_browser.twig';

    public function setUp(Request $request)
    {
        $this->translations = $this->model('Translations');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, $this->browserTemplate);
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new TranslationAdminForm();

        return $this->handleEdit($request, $this->translations, $formBlueprint, 'translations_admin_edit', '@TranslationHelper/admin/edit_translation.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->translations->find()
            ->setSearchFields(array('id'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TranslationHelper/admin/translations_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->translations);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new TranslationsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
