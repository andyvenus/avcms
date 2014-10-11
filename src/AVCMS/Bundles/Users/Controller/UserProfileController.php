<?php
/**
 * User: Andy
 * Date: 07/10/2014
 * Time: 18:26
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Form\ChangePasswordForm;
use AVCMS\Bundles\Users\Form\EditProfileForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class UserProfileController extends Controller
{
    /**
     * Display's a user's profile
     *
     * @param $slug
     * @return Response
     */
    public function userProfileAction($slug)
    {
        $users = $this->model('Users');

        $user = $users->getOne($slug);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $usersOwnProfile = false;
        if ($this->activeUser()->getId() == $user->getId()) {
            $usersOwnProfile = true;
        }

        return new Response($this->render('@Users/profile.twig', ['profile_user' => $user, 'users_own_profile' => $usersOwnProfile]));
    }

    /**
     * The logged-in user's edit profile page
     *
     * @param Request $request
     * @return Response
     */
    public function editUserProfileAction(Request $request)
    {
        $context = $this->container->get('security.context');

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new InsufficientAuthenticationException;
        }

        $user = $context->getToken()->getUser();

        $editProfileForm = new EditProfileForm();
        $form = $this->buildForm($editProfileForm, $request, [$user]);

        if ($form->isValid()) {
            $form->saveToEntities();

            if ($file = $form->getData('avatar_file')) {
                $filename = $user->getId().'-avatar.'.$file->guessExtension();
                $file->move($this->bundle->config->avatar_dir, $filename);

                $user->setAvatar($filename);
            }

            if ($file = $form->getData('cover_image')) {
                $filename = $user->getId().'-cover.'.$file->guessExtension();
                $file->move($this->bundle->config->avatar_dir, $filename);

                $user->setCoverImage($filename);
            }

            $users = $this->model('Users');
            $users->save($user);
        }

        return new Response($this->render('@Users/edit_profile.twig', ['form' => $form->createView()]));
    }

    public function manageEmailPasswordAction(Request $request)
    {
        $context = $this->container->get('security.context');

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new InsufficientAuthenticationException;
        }

        $form = $this->buildForm(new ChangePasswordForm(), $request);

        if ($form->isSubmitted()) {
            if ($form->getData('password1') === $form->getData('password2')) {
                $encodedPassword = $this->container->get('users.bcrypt_encoder')->encodePassword($form->getData('password1'), null);
                $user = $this->activeUser();
                $user->setPassword($encodedPassword);

                $this->model('Users')->save($user);
            }
            else {
                $form->addCustomErrors([new FormError('password2', 'The entered passwords do not match', true)]);
            }
        }

        return new Response($this->render('@Users/manage_email_password.twig', ['password_form' => $form->createView()]));
    }
}