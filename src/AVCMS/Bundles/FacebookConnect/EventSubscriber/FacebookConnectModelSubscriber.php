<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\FacebookConnect\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FacebookConnectModelSubscriber implements EventSubscriberInterface
{
    public function addOverflowEntity(CreateModelEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof Users) {
            return;
        }

        $model->addOverflowEntity('facebook', 'AVCMS\Bundles\FacebookConnect\Model\FacebookConnectOverflow');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.create' => ['addOverflowEntity']
        ];
    }
}
