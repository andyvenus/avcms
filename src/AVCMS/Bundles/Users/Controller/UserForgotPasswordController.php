<?php
/**
 * User: Andy
 * Date: 07/12/14
 * Time: 10:44
 */

namespace AVCMS\Bundles\Users\Controller;

use AV\Form\FormError;
use AVCMS\Bundles\Users\Form\ChangePasswordForm;
use AVCMS\Bundles\Users\Form\ForgotPasswordForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserForgotPasswordController extends Controller
{
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->buildForm(new ForgotPasswordForm(), $request);

        if ($form->isValid()) {
            $email = $form->getData('email');

            $user = $this->model('Users')->getByUsernameOrEmail($email);

            $resets = $this->model('PasswordResets');

            if ($resets->userHasRecentReset($user->getId())) {
                $form->addCustomErrors([new FormError('email', 'That user already has an active reset request. Please wait to try again.', true)]);
            }
            else {
                $newReset = $resets->newEntity();
                $newReset->setUserId($user->getId());
                $newReset->setCode(bin2hex(random_bytes(20)));
                $newReset->setGenerated(time());

                $resets->insert($newReset);

                $mailer = $this->container->get('mailer');

                $email = $mailer->newEmail($this->trans('Password reset request'), $this->render("@Users/email/email.password_reset.twig", ['reset' => $newReset]), 'text/html', 'UTF-8');
                $email->setTo($user->getEmail());

                $mailer->send($email);
            }
        }

        return new Response($this->render('@Users/forgot_password.twig', ['password_reset_form' => $form->createView()]));
    }

    public function forgotPasswordProcessAction(Request $request)
    {
        $resets = $this->model('PasswordResets');

        $reset = $resets->findReset($request->get('userId'), $request->get('code'));

        if ($reset === null) {
            return $this->redirect('home', [], 302, 'error', $this->trans("The password reset request is invalid"));
        }

        $form = $this->buildForm(new ChangePasswordForm(), $request);

        if ($form->isSubmitted()) {
            if ($form->getData('password1') !== $form->getData('password2')) {
                $form->addCustomErrors([new FormError('password2', 'The entered passwords do not match', true)]);
            }
            else {
                $user = $this->model('Users')->getOne($request->get('userId'));
                $encodedPassword = $this->container->get('security.bcrypt_encoder')->encodePassword($form->getData('password1'), null);
                $user->setPassword($encodedPassword);
                $this->model('Users')->update($user);

                $resets->deleteUserResets($user->getId());

                return $this->redirect('home', [], 302, 'info', $this->trans("Your password has been reset"));
            }
        }

        return new Response($this->render('@Users/forgot_password.twig', ['password_reset_form' => $form->createView()]));
    }
} 
