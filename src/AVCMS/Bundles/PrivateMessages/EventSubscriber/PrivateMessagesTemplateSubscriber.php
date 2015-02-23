<?php
/**
 * User: Andy
 * Date: 23/02/15
 * Time: 13:03
 */

namespace AVCMS\Bundles\PrivateMessages\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PrivateMessagesTemplateSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    private $translator;

    private $tokenStorage;

    private $authChecker;

    public function __construct(UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker)
    {
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }

    public function getMessageCount(OutletEvent $event)
    {
        if ($event->getOutletName() !== 'user_options' && $event->getOutletName() !== 'user_profile_buttons') {
            return;
        }

        if (!$this->authChecker->isGranted('PERM_PRIVATE_MESSAGES')) {
            return;
        }

        if ($event->getOutletName() === 'user_options') {
            $user = $event->getVar('user');

            $totalUnread = $user->messages->getTotalUnread();

            $event->addContent('
                &nbsp;<a href="'.$this->urlGenerator->generate('private_messages_inbox').'">
                    <span class="glyphicon glyphicon-inbox"></span>
                    <span class="avcms-unread-message-count">'.$totalUnread.'</span>
                </a>
            ');
        }
        elseif ($event->getOutletName() === 'user_profile_buttons') {
            $currentUser = $this->tokenStorage->getToken()->getUser();
            $user = $event->getVar('user');

            if ($currentUser->getId() == $user->getId()) {
                return;
            }

            $event->addContent('
                <a class="btn btn-default" href="'.$this->urlGenerator->generate('send_private_message', ['recipient' => $user->getId()]).'">
                    <span class="glyphicon glyphicon-envelope"></span>
                    '.$this->translator->trans('Send Message').'
                </a>
            ');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'getMessageCount',
        ];
    }
}
