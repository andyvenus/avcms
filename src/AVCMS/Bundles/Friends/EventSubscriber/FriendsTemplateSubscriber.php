<?php
/**
 * User: Andy
 * Date: 15/03/15
 * Time: 15:56
 */

namespace AVCMS\Bundles\Friends\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use AVCMS\Bundles\Friends\Model\FriendRequests;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FriendsTemplateSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    private $translator;

    private $tokenStorage;

    private $authChecker;

    private $friendRequests;

    public function __construct(UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, FriendRequests $friendRequests)
    {
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
        $this->friendRequests = $friendRequests;
    }

    public function getOutlets(OutletEvent $event)
    {
        if ($event->getOutletName() !== 'user_options' && $event->getOutletName() !== 'user_profile_buttons') {
            return;
        }

        $currentUser = $this->tokenStorage->getToken()->getUser();
        $user = $event->getVar('user');

        if (!$currentUser->getId()) {
            return;
        }

        if ($event->getOutletName() === 'user_options') {
            $friendRequestsCount = $this->friendRequests->getRequestsCount($currentUser->getId());
            if ($friendRequestsCount === 0) {
                $friendRequestsCount = null;
            }

            $event->addContent('
                &nbsp;<a href="'.$this->urlGenerator->generate('friends').'">
                    <span class="glyphicon glyphicon-user" data-toggle="tooltip" data-placement="bottom" title="'.$this->translator->trans('Friends').'"></span>
                    '.$friendRequestsCount.'
                </a>
            ');
        }
        elseif ($event->getOutletName() === 'user_profile_buttons') {
            if ($currentUser->getId() == $user->getId()) {
                return;
            }

            if ($this->friendRequests->requestExists($currentUser->getId(), $user->getId())) {
                $btnAction = 'cancel-request';
                $btnText = 'Cancel Friend Request';
                $icon = 'remove-circle';
            }
            else {
                $btnAction = 'send-request';
                $btnText = 'Add Friend';
                $icon = 'ok-circle';
            }

            $event->addContent('
                <button type="button" class="btn btn-default avcms-friend-request-button" data-action="'.$btnAction.'" data-id="'.$user->getId().'">
                    <span class="glyphicon glyphicon-'.$icon.'"></span>
                    '.$this->translator->trans($btnText).'
                </button>
            ');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'getOutlets',
        ];
    }
}
