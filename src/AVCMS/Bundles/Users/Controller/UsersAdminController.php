<?php

namespace AVCMS\Bundles\Users\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Users\Event\DeleteUserEvent;
use AVCMS\Bundles\Users\Form\UserAdminForm;
use AVCMS\Bundles\Users\Form\UsersAdminFiltersForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UsersAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Users\Model\Users
     */
    protected $users;

    protected $browserTemplate = '@Users/admin/users_browser.twig';

    public function setUp(Request $request)
    {
        $this->users = $this->model('Users');

        if (!$this->isGranted('ADMIN_USERS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Users/admin/users_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new UserAdminForm($request->get('id', 0));

        return $this->handleEdit($request, $this->users, $formBlueprint, 'users_admin_edit', '@Users/admin/edit_user.twig', '@Users/admin/users_browser.twig', array());
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
                $encodedPassword = $this->container->get('security.bcrypt_encoder')->encodePassword($form->getData('password1'), null);
                $user->setPassword($encodedPassword);

                $this->users->save($user);
            }
            else {
                $form->addCustomErrors([new FormError('password2', 'The entered passwords do not match', true)]);
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        return new Response($this->renderAdminSection('@Users/admin/admin_change_user_password.twig', $request->get('ajax_depth'), ['item' => $user, 'form' => $form->createView()]));
    }

    public function finderAction(Request $request)
    {
        $finder = $this->users->find()
            ->setSearchFields(array('username', 'last_ip', 'email'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Users/admin/users_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
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

    public function userOptionsAction(Request $request)
    {
        // remove accounts
        $removeAccountsFormBlueprint = new FormBlueprint();
        $removeAccountsFormBlueprint->add('remove_accounts_age', 'select', [
            'label' => 'Accounts Created',
            'choices' => [
                '604800' => '1 week ago and older',
                '1209600' => '2 weeks ago and older',
                '2592000' => '1 month ago and older'
            ],
            'strict' => true
        ]);

        $removeAccountsForm = $this->buildForm($removeAccountsFormBlueprint, $request);

        if ($removeAccountsForm->isValid()) {
            $age = time() - $removeAccountsForm->getData('remove_accounts_age');

            $totalAccounts = $this->users->query()->count();

            $this->users->query()->where('joined', '<', $age)->where('last_activity', 0)->delete();

            $totalDeleted = $totalAccounts - $this->users->query()->count();

            $removeAccountsFormBlueprint->setSuccessMessage($totalDeleted.' '.$this->trans('Accounts Deleted'));

            return new JsonResponse(['form' => $removeAccountsForm->createView()->getJsonResponseData(), 'success' => true, 'total_deleted' => $totalDeleted]);
        }

        // main page

        return new Response($this->renderAdminSection('@Users/admin/user_options.twig', $request->get('ajax_depth'), ['remove_accounts_form' => $removeAccountsForm->createView()]));
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new UsersAdminFiltersForm())->createView();

        return $template_vars;
    }
} 
