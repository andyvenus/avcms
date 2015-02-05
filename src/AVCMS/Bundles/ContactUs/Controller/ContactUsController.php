<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 12:45
 */

namespace AVCMS\Bundles\ContactUs\Controller;

use AVCMS\Bundles\ContactUs\Form\ContactUsForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactUsController extends Controller
{
    public function contactUsAction(Request $request)
    {
        if (!$this->setting('enable_contact_us') || !$this->setting('admin_emails')) {
            throw $this->createNotFoundException('No admin email set up');
        }

        $form = $this->buildForm(new ContactUsForm($this->userLoggedIn()), $request);

        if ($form->isValid()) {
            $mailer = $this->container->get('mailer');

            $subject = '['.$this->setting('site_name').'] '.$form->getData('subject');

            $email = $mailer->newEmail($subject, $form->getData('body'));

            $adminAddresses = explode(',', (string) $this->setting('admin_emails'));
            $email->setTo($adminAddresses);

            if ($this->userLoggedIn()) {
                $emailAddress = $this->activeUser()->getEmail();
            }
            else {
                $emailAddress = $form->getData('email');
            }

            $email->setFrom($emailAddress);

            $mailer->send($email);

            return $this->redirect('home', [], 302, 'success', $this->trans('Your message has been sent, we will respond to {email}', ['email' => $emailAddress]));
        }

        return new Response($this->render('@ContactUs/contact_us.twig', ['form' => $form->createView()]));
    }
}
