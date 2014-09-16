<?php

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Bundles\CmsFoundation\Form\ModulePositionsAdminFiltersForm;
use AVCMS\Bundles\CmsFoundation\Form\ModulePositionAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use AVCMS\Bundles\CmsFoundation\Form\AdminModuleForm;
use AVCMS\Core\Module\Exception\ModuleNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ModulesAdminController extends AdminBaseController
{
    protected $browserTemplate = '@CmsFoundation/modules_browser.twig';

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\ModulePositions
     */
    protected $modulePositions;

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\Modules
     */
    protected $modules;

    public function setUp(Request $request)
    {
        $this->modulePositions = $this->model('ModulePositions');
        $this->modules = $this->model('Modules');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@CmsFoundation/modules_browser.twig');
    }

    public function addModuleAction(Request $request, $position)
    {
        $position = $this->modulePositions->getOne($position);

        if (!$position) {
            throw $this->createNotFoundException("Module position not found");
        }

        $moduleManager = $this->container->get('module_manager');
        $modules = $moduleManager->getAllModules();

        return new Response($this->renderAdminSection('@CmsFoundation\add_module.twig', $request->get('ajax_depth'), array('item' => $position, 'modules' => $modules)));
    }

    public function addSelectedModuleAction(Request $request)
    {
        if ($id = $request->attributes->get('id')) {
            $moduleConfig = $this->modules->getOne($id);
            if (!$moduleConfig) {
                throw $this->createNotFoundException("Module not found");
            }
        }
        else {
            $moduleConfig = $this->modules->newEntity();
            $moduleConfig->setPosition($request->get('position'));
            $moduleConfig->setModule($request->get('module'));
        }

        $position = $this->modulePositions->getOne($moduleConfig->getPosition());

        if (!$position) {
            throw $this->createNotFoundException("Module position not found");
        }

        $moduleManager = $this->container->get('module_manager');

        try {
            $module = $moduleManager->getModule($moduleConfig->getModule());
        }
        catch (ModuleNotFoundException $e) {
            throw $this->createNotFoundException("Module not found");
        }

        $form = new AdminModuleForm();
        if (isset($module['user_settings'])) {
            $form->createFieldsFromArray($module['user_settings'], 'settings_array');
        }

        $formHandler = $this->buildForm($form, $request, $moduleConfig);

        if ($formHandler->isSubmitted()) {
            if ($formHandler->isValid()) {
                $formHandler->saveToEntities();
                $this->modules->save($moduleConfig);
            }

            return new JsonResponse(array(
                'form' => $formHandler->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl('modules_manage_position', array('id' => $position->getId()))
            ));
        }

        return new Response($this->renderAdminSection('@CmsFoundation\add_module_selected.twig', $request->get('ajax_depth'), array(
            'item' => $moduleConfig,
            'position' => $position,
            'module' => $module,
            'form' => $formHandler->createView()
        )));
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new ModulePositionAdminForm();

        return $this->handleEdit($request, $this->modulePositions, $formBlueprint, 'module_positions_admin_edit', '@CmsFoundation/edit_module_position.twig', '@CmsFoundation/modules_browser.twig', array());
    }

    public function managePositionModulesAction(Request $request)
    {
        $position = $this->modulePositions->getOne($request->get('id'));

        if (!$position) {
            throw $this->createNotFoundException('Module position not found');
        }

        $modules = $this->modules->find()->where('position', $request->get('id'))->customOrder('order', 'asc')->get();

        return new Response($this->renderAdminSection('@CmsFoundation/manage_position_modules.twig', $request->get('ajax_depth'), array(
            'item' => $position,
            'modules' => $modules,
        )));
    }

    public function saveOrderAction(Request $request, $position)
    {
        if (!$request->get('modules_order')) {
            throw $this->createNotFoundException("No modules ordering data found in request");
        }

        $modules = $this->modules->find()->where('position', $position)->get();
        $order = $request->get('modules_order');

        $i = 0;
        foreach ($order as $id => $parent) {
            $i++;

            if (isset($modules[$id])) {
                $modules[$id]->setOrder($i);

                $this->modules->update($modules[$id]);
            }
        }

        return new JsonResponse(array('success' => true));
    }

    public function finderAction(Request $request)
    {
        $finder = $this->modulePositions->find()
            ->setSearchFields(array('name'))
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@CmsFoundation/module_positions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->modules);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->modulePositions);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new ModulePositionsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 