<?php
/**
 * User: Andy
 * Date: 10/10/2014
 * Time: 19:42
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Form\UserGroupsAdminFiltersForm;
use AVCMS\Bundles\Users\Form\UserGroupAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use AV\Form\FormBlueprint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserGroupsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Users\Model\UserGroups
     */
    protected $userGroups;

    protected $browserTemplate = '@Users/admin/user_groups_browser.twig';

    public function setUp(Request $request)
    {
        $this->userGroups = $this->model('UserGroups');

        if (!$this->isGranted('ADMIN_USER_GROUPS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, '@Users/admin/user_groups_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new UserGroupAdminForm();

        return $this->handleEdit($request, $this->userGroups, $formBlueprint, 'user_groups_admin_edit', '@Users/admin/edit_user_group.twig', '@Users/admin/user_groups_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->userGroups->find()
            ->setSearchFields(array('name'))
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Users/admin/user_groups_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->userGroups);
    }

    public function manageGroupPermissionsAction(Request $request)
    {
        $userGroup = $this->userGroups->getOne($request->get('id'));

        if (!$userGroup) {
            throw $this->createNotFoundException();
        }

        $permissionsModel = $this->model('Permissions');
        $permissions = $permissionsModel->getAll();

        $groupPermissionsModel = $this->model('GroupPermissions');
        $groupPermissions = $groupPermissionsModel->getRolePermissions($userGroup->getId());

        $formBlueprint = new FormBlueprint();
        $formBlueprint->setSuccessMessage('Permissions Updated');

        foreach ($permissions as $permission) {
            $formBlueprint->add("permissions[{$permission->getId()}]", 'radio', [
                'label' => $permission->getName(),
                'choices' => [
                    'default' => 'Default',
                    '1' => 'Allow',
                    '0' => 'Deny'
                ],
                'default' => 'default'
            ]);
        }

        $form = $this->buildForm($formBlueprint);
        $form->mergeData(['permissions' => $groupPermissions]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $groupPermissionsModel->deleteRolePermissions($userGroup->getId());

            foreach ($form->getData('permissions') as $permissionId => $permissionValue) {
                if ($permissionValue != 'default') {
                    $groupPermissionsModel->insertPermission($userGroup->getId(), $permissionId, $permissionValue);
                }
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        return new Response($this->renderAdminSection('@Users/admin/manage_user_group_permissions.twig', $request->get('ajax_depth'),
            ['form' => $form->createView(), 'permissions' => $permissions, 'item' => $userGroup]
        ));
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new UserGroupsAdminFiltersForm())->createView();

        return $template_vars;
    }
}