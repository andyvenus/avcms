<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 13:50
 */

namespace AVCMS\Bundles\Links\Controller;

use AVCMS\Bundles\Links\Form\LinkExchangeForm;
use AVCMS\Bundles\Links\Form\LinkExchangeSuccessForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinksController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Links\Model\Links
     */
    private $links;

    /**
     * @var \AVCMS\Bundles\Referrals\Model\Referrals
     */
    private $referrals;

    public function setUp()
    {
        $this->links = $this->model('Links');
        $this->referrals = $this->model('AVCMS\Bundles\Referrals\Model\Referrals');
    }

    public function linkExchangeAction(Request $request)
    {
        $link = $this->links->newEntity();
        $link->setPublished(0);
        $referral = $this->referrals->newEntity();

        $formBlueprint = new LinkExchangeForm();

        if (!$this->userLoggedIn()) {
            $formBlueprint->add('email', 'text', [
                'label' => 'Email',
                'required' => true,
                'validation' => [
                    ['rule' => 'EmailAddress']
                ]
            ]);
            $formBlueprint->add('recaptcha', 'recaptcha');
        }

        $form = $this->buildForm($formBlueprint, $request, [$link, $referral]);

        if ($form->isValid()) {
            $form->saveToEntities();

            $referral->setType('link');
            $referral->setName($form->getData('anchor'));

            if ($this->userLoggedIn()) {
                $referral->setUserId($this->activeUser()->getId());
                $referral->setUserEmail($this->activeUser()->getEmail());
            }

            $this->referrals->save($referral);

            $link->setReferralId($referral->getId());

            $this->links->save($link);

            $url = $this->generateUrl('home', ['ref_id' => $referral->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            $linkExchangeSuccessForm = $this->buildForm(new LinkExchangeSuccessForm($url, $this->setting('site_name')), $request);

            $emailSubject = $this->trans('Your link exchange with {site_name}', ['site_name' => $this->setting('site_name')]);

            $mailer = $this->container->get('mailer');
            $email = $mailer->newEmail($emailSubject, $this->render('@Links/email/email.link_exchange.twig', ['url' => $url]), 'text/html');
            $email->setTo($referral->getUserEmail());
            $mailer->send($email);

            return new Response($this->render('@Links/link_exchange_complete.twig', [
                'link' => $link,
                'referral' => $referral,
                'form' => $linkExchangeSuccessForm->createView()
            ]));
        }

        return new Response($this->render('@Links/link_exchange.twig', ['form' => $form->createView()]));
    }
}
