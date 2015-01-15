<?php

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\CmsFoundation\Form\AdminModuleForm;
use AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider\RouteChoicesProvider;
use AVCMS\Bundles\CmsFoundation\Form\ModulePositionAdminForm;
use AVCMS\Bundles\CmsFoundation\Form\ModulePositionsAdminFiltersForm;
use AVCMS\Core\Module\Exception\ModuleNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ModulesAdminController extends AdminBaseController
{
    protected $browserTemplate = '@CmsFoundation/admin/modules_browser.twig';

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

        if (!$this->isGranted('ADMIN_MODULES')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@CmsFoundation/admin/modules_browser.twig');
    }

    public function addModuleAction(Request $request, $position)
    {
        $position = $this->modulePositions->getOne($position);

        if (!$position) {
            throw $this->createNotFoundException("Module position not found");
        }

        $moduleManager = $this->container->get('module_manager');
        $allModules = $moduleManager->getAllModules();

        $modulePositions = [$position->getType() => array()];
        foreach ($allModules as $moduleId => $module) {
            if ($module->getType() == $position->getType() || ($position->getGlobalModules() == 1 && $module->getType() == 'standard')) {
                $modulePositions[$module->getType()][$moduleId] = $module;
            }
        }

        return new Response($this->renderAdminSection('@CmsFoundation/admin/add_module.twig', $request->get('ajax_depth'), array('item' => $position, 'module_positions' => $modulePositions)));
    }

    public function manageSelectedModuleAction(Request $request)
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

        $templateStyles = $moduleManager->getTemplateTypes();

        $acceptedTemplates = $module->getAcceptedTemplateTypes();
        foreach ($acceptedTemplates as $acceptedTemplate) {
            if (isset($templateStyles[$acceptedTemplate])) {
                $templateList[$acceptedTemplate] = $templateStyles[$acceptedTemplate]['name'];
            }
        }

        $permsProvider = $this->container->get('permissions.choices_provider');

        $form = new AdminModuleForm(new RouteChoicesProvider($this->get('router'), 'frontend'), $templateList, $this->getTemplatesList($position, $moduleConfig->getTemplateType()), $permsProvider, $module->getDefaultPermissions());

        if ($module->isCachable()) {
            $form->add('cache_time', 'text', array('label' => "Seconds until cache expires (0 for no cache)", 'default' => $module->getDefaultCacheTime()));
        }

        $form->createFieldsFromArray($module->getUserSettings(), 'settings_array');

        $formHandler = $this->buildForm($form, $request, $moduleConfig);

        if ($formHandler->isSubmitted()) {
            if ($formHandler->isValid()) {
                $formHandler->saveToEntities();
                $this->modules->save($moduleConfig);
            }

            $this->container->get('module_manager')->clearCaches([$moduleConfig->getModule()]);

            return new JsonResponse(array(
                'form' => $formHandler->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl('modules_manage_position', array('id' => $position->getId()))
            ));
        }

        return new Response($this->renderAdminSection('@CmsFoundation/admin/add_module_selected.twig', $request->get('ajax_depth'), array(
            'item' => $moduleConfig,
            'position' => $position,
            'module' => $module,
            'form' => $formHandler->createView()
        )));
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new ModulePositionAdminForm();

        return $this->handleEdit($request, $this->modulePositions, $formBlueprint, 'module_positions_admin_edit', '@CmsFoundation/admin/edit_module_position.twig', '@CmsFoundation/admin/modules_browser.twig', array());
    }

    public function managePositionModulesAction(Request $request)
    {
        $position = $this->modulePositions->getOne($request->get('id'));

        if (!$position) {
            throw $this->createNotFoundException('Module position not found');
        }

        $modules = $this->container->get('module_manager')->getPositionModules($request->get('id'), array(), false, false, true);

        return new Response($this->renderAdminSection('@CmsFoundation/admin/manage_position_modules.twig', $request->get('ajax_depth'), array(
            'item' => $position,
            'modules' => $modules,
        )));
    }

    public function deletePositionAction(Request $request)
    {
        $id = $request->request->get('ids');

        $position = $this->modulePositions->getOne($id);

        if (!$position) {
            throw $this->createNotFoundException();
        }

        if ($position->getActive() == 1) {
            return new JsonResponse(['success' => false, 'error' => 'Cannot delete active position']);
        }

        $this->modulePositions->delete($position);

        $this->modules->query()->where('position', $id)->delete();

        return new JsonResponse(['success' => true]);
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
            ->setResultsPerPage(20)
            ->setSearchFields(array('name'))
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@CmsFoundation/admin/module_positions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->modules);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->modulePositions);
    }

    public function getTemplatesListAction($positionId, $templateType)
    {
        $position = $this->modulePositions->getOne($positionId);

        return new JsonResponse($this->getTemplatesList($position, $templateType));
    }

    private function getTemplatesList($position, $templateType)
    {
        $prependTemplate = null;

        if ($position->getEnvironment() == 'frontend') {
            $dir = $this->setting('template').'/modules/'.$templateType.'/*.twig';
        }
        else {
            $prependTemplate = "@".$position->getEnvironment().'/';
            $dir = 'templates/admin/avcms/modules/'.$templateType.'/*.twig';
        }

        $templates = ['0' => 'Default'];
        foreach (glob($dir) as $template) {
            $templates[$prependTemplate.'modules/'.$templateType.'/'.basename($template)] = basename($template);
        }

        return $templates;
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new ModulePositionsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 
