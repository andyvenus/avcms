<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 11:41
 */

namespace AVCMS\Bundles\Referrals\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReferralOverflowSubscriber implements EventSubscriberInterface
{
    public function addOverflowEntity(CreateModelEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof Users) {
            return;
        }

        $model->addOverflowEntity('referral', 'AVCMS\Bundles\Referrals\Model\ReferralOverflow');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.create' => ['addOverflowEntity']
        ];
    }
}
