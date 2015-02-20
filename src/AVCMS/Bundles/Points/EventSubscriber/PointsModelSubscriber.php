<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AV\Model\Event\CreateModelEvent;
use AV\Model\Event\QueryBuilderModelJoinEvent;
use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PointsModelSubscriber implements EventSubscriberInterface
{
    public function addOverflowEntity(CreateModelEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof Users) {
            return;
        }

        $model->addOverflowEntity('points', 'AVCMS\Bundles\Points\Model\PointsOverflow');
    }

    public function addJoinColumn(QueryBuilderModelJoinEvent $event)
    {
        if (!$event->getJoinModel() instanceof Users) {
            return;
        }

        $columns = $event->getColumns();
        $columns[] = 'points__points';
        $event->setColumns($columns);
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.create' => ['addOverflowEntity'],
            'query_builder.model_join' => ['addJoinColumn']
        ];
    }
}
