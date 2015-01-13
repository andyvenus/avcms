<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\FacebookConnect\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FacebookConnectSubscriber implements EventSubscriberInterface
{
    public function addOverflowEntity(CreateModelEvent $event)
    {
        $event->getModel()->addOverflowEntity('facebook', 'AVCMS\Bundles\FacebookConnect\Model\FacebookConnectOverflow');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.create' => ['addOverflowEntity']
        ];
    }
}
