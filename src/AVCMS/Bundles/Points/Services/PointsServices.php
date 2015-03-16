<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 14:37
 */

namespace AVCMS\Bundles\Points\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PointsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('points_manager', 'AVCMS\Bundles\Points\PointsManager')
            ->setArguments([new Reference('session'), new Reference('security.token_storage'), new Reference('users.model'), new Reference('settings_manager')])
        ;

        $container->register('points_user_form.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\PointsExtendUserFormSubscriber')
            ->addTag('event.subscriber')
        ;

        $container->register('display_points.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\DisplayPointsSubscriber')
            ->setArguments([new Reference('settings_manager'), new Reference('security.token_storage')])
            ->addTag('event.subscriber')
        ;

        $container->register('points_model.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\PointsModelSubscriber')
            ->addTag('event.subscriber')
        ;

        $container->register('comment_points.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\CommentPointsSubscriber')
            ->setArguments([new Reference('points_manager')])
            ->addTag('event.subscriber')
        ;

        $container->register('rating_points.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\RatePointsSubscriber')
            ->setArguments([new Reference('points_manager')])
            ->addTag('event.subscriber')
        ;

        $container->register('report_points.subscriber', 'AVCMS\Bundles\Points\EventSubscriber\ReportPointsSubscriber')
            ->setArguments([new Reference('points_manager')])
            ->addTag('event.subscriber')
        ;
    }
}
