<?php
/**
 * User: Andy
 * Date: 23/02/15
 * Time: 14:18
 */

namespace AVCMS\Bundles\PrivateMessages\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PrivateMessagesModelSubscriber implements EventSubscriberInterface
{
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
            'model.create' => ['addOverflowEntity']
        ];
    }
}
