<?php

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use AVCMS\Bundles\Users\Event\DeleteUserEvent;
use AVCMS\Bundles\Users\Form\UserAdminForm;
use AVCMS\Bundles\Users\Form\UsersAdminFiltersForm;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UsersAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Users\Model\Users
     */
    protected $users;

    protected $browserTemplate = '@Users/users_browser.twig';

    public function setUp(Request $request)
    {
        $this->users = $this->model('Users');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Users/users_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new UserAdminForm();

        return $this->handleEdit($request, $this->users, $formBlueprint, 'users_admin_edit', '@Users/edit_user.twig', '@Users/users_browser.twig', array());
    }

    public function changePasswordAction(Request $request, $id)
    {
        $user = $this->users->getOne($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $formBlueprint = new FormBlueprint();
        $formBlueprint->add('password1', 'password', ['label' => 'New Password']);
        $formBlueprint->add('password2', 'password', ['label' => 'Confirm Password']);
        $formBlueprint->setSuccessMessage('Password updated');

        $form = $this->buildForm($formBlueprint, $request, $user);

        if ($form->isSubmitted()) {
            if ($form->getData('password1') == $form->getData('password2')) {
                $encodedPassword = $this->container->get('users.bcrypt_encoder')->encodePassword($form->getData('password1'), null);
                $user->setPassword($encodedPassword);

                $this->users->save($user);
            }
            else {
                $form->addCustomErrors([new FormError('password2', 'The entered passwords do not match', true)]);
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        return new Response($this->renderAdminSection('@Users/admin_change_user_password.twig', $request->get('ajax_depth'), ['item' => $user, 'form' => $form->createView()]));
    }

    public function finderAction(Request $request)
    {
        $finder = $this->users->find()
            ->setSearchFields(array('username', 'last_ip'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Users/users_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        if (!$request->get('ids')) {
            return new JsonResponse(array('success' => 0, 'error' => 'No ids set'));
        }

        $idsArray = explode(',', $request->get('ids'));

        foreach ($idsArray as $id) {
            $this->users->deleteById($id);

            $event = new DeleteUserEvent($id);
            $this->dispatchEvent('delete.user', $event);
        }

        return new JsonResponse(array('success' => 1));
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new UsersAdminFiltersForm())->createView();

        return $template_vars;
    }
} 