<?php
/**
 * User: Andy
 * Date: 23/02/15
 * Time: 13:03
 */

namespace AVCMS\Bundles\PrivateMessages\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PrivateMessagesSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getMessageCount(OutletEvent $event)
    {
        if ($event->getOutletName() !== 'user_options') {
            return;
        }

        $user = $event->getVar('user');

        $totalUnread = $user->messages->getTotalUnread();

        $event->addContent('
            &nbsp;<a href="'.$this->urlGenerator->generate('private_messages_inbox').'">
                <span class="glyphicon glyphicon-inbox"></span>
                <span class="avcms-unread-message-count">'.$totalUnread.'</span>
            </a>
        ');
    }

    public function addOverflowEntity(CreateModelEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof Users) {
            return;
        }

        $model->addOverflowEntity('messages', 'AVCMS\Bundles\PrivateMessages\Model\MessagesOverflow');
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'getMessageCount',
            'model.create' => ['addOverflowEntity']
        ];
    }
}
