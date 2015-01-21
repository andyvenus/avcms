<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 20:01
 */

namespace AVCMS\Bundles\Referrals\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReferralServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('subscriber.referral', 'AVCMS\Bundles\Referrals\EventSubscriber\ReferralSubscriber')
            ->setArguments([new Reference('referrals.model'), new Reference('users.model'), new Reference('hitcounter'), new Reference('session')])
            ->addTag('event.subscriber');
        ;

        $container->register('subscriber.referral_overflow', 'AVCMS\Bundles\Referrals\EventSubscriber\ReferralOverflowSubscriber')
            ->addTag('event.subscriber');
        ;

        $container->register('referrals.model', 'AVCMS\Bundles\Referrals\Model\Referrals')
            ->setArguments(['AVCMS\Bundles\Referrals\Model\Referrals'])
            ->setFactory([new Reference('model_factory'), 'create'])
        ;
    }
}
