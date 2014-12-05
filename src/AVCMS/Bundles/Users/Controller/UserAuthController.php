<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 16:09
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Form\RegistrationForm;
use AVCMS\Bundles\Users\Model\EmailValidationKey;
use AVCMS\Core\Controller\Controller;
use AVCMS\Bundles\Users\Form\LoginForm;
use AV\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Util\SecureRandom;

class UserAuthController extends Controller
{
    /**
     * Shows a login form and displays any authentication errors
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $loginFormBlueprint = new LoginForm();
        $loginFormBlueprint->setAction($this->generateUrl('login_check'));
        $loginForm = $this->buildForm($loginFormBlueprint);

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $errorMessage = $request->attributes->get(Security::AUTHENTICATION_ERROR)->getMessage();
        } elseif($request->getSession()->get(Security::AUTHENTICATION_ERROR)) {
            $errorMessage = $request->getSession()->get(Security::AUTHENTICATION_ERROR)->getMessage();
        }
        if ($request->get('reauth')) {
            $loginForm->addCustomErrors([new FormError(null, 'Please re-authenticate to access this area')]);
        }

        if (isset($errorMessage)) {
            $loginForm->addCustomErrors(array(new FormError(null, $errorMessage, true)));
        }

        $loginForm->setData('username', $request->getSession()->get(Security::LAST_USERNAME));

        return new Response($this->render('@Users/login.twig', array('login_form' => $loginForm->createView())));
    }

    /**
     * Handles all aspects of user registration
     *
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if (!$this->setting('users_enabled')) {
            throw $this->createNotFoundException();
        }

        $users = $this->model('Users');
        $user = $users->newEntity();

        $form = $this->buildForm(new RegistrationForm(), $request, $user);

        if ($form->isValid() && $form->getData('password1') == $form->getData('password2')) {
            $encodedPassword = $this->container->get('security.bcrypt_encoder')->encodePassword($form->getData('password1'), null);
            $user->setPassword($encodedPassword);
            $form->saveToEntities();

            $slugGenerator = $this->container->get('slug.generator');
            $user->setSlug($slugGenerator->slugify($user->getUsername()));

            $user->setJoined(time());
            $user->setLastIp($request->getClientIp());

            if ($this->setting('validate_email_addresses')) {
                $user->setRoleList('ROLE_NOT_VALIDATED');
            }
            else {
                $user->setRoleList('ROLE_USER');
            }

            $users->insert($user);

            if ($this->setting('validate_email_addresses')) {
                $randomGen = new SecureRandom();

                $emailKey = new EmailValidationKey();
                $emailKey->setCode(bin2hex($randomGen->nextBytes(20)));
                $emailKey->setGenerated(time());
                $emailKey->setUserId($user->getId());
                $this->model('EmailValidationKeys')->insert($emailKey);

                $mailer = $this->container->get('mailer');

                $email = $mailer->newEmail($this->trans('Validate your new account'), $this->render("@Users/email/email.validate_address.twig", ['emailKey' => $emailKey]), 'text/html', 'UTF-8');
                $email->setTo($user->getEmail());

                $mailer->send($email);
            }

            return new Response($this->render('@Users/register_complete.twig', ['emailValidation' => $this->setting('validate_email_addresses'), 'user' => $user]));
        }
        elseif($form->isSubmitted() && $form->getData('password1') != $form->getData('password2')) {
            $form->addCustomErrors([new FormError('password2', 'The entered passwords do not match', true)]);
        }

        return new Response($this->render('@Users/register.twig', array('registration_form' => $form->createView())));
    }

    /**
     * Validates a user's email address
     *
     * @param $userId
     * @param $code
     * @return Response
     */
    public function validateEmailAction($userId, $code)
    {
        $emailValidationKeys = $this->model('EmailValidationKeys');
        if ($emailValidationKeys->isValidKey($userId, $code)) {
            $users = $this->model('Users');
            $users->query()->where('id', $userId)->update(['role_list' => 'ROLE_USER']);

            $emailValidationKeys->deleteUserKey($userId);

            $success = true;
        }
        else {
            $success = false;
        }

        return new Response($this->render('@Users/validate_email.twig', ['success' => $success]));
    }
}