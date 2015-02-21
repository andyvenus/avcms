<?php
/**
 * User: Andy
 * Date: 21/02/15
 * Time: 11:05
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AV\Model\Event\ModelInsertEvent;
use AVCMS\Bundles\Points\PointsManager;
use AVCMS\Bundles\Reports\Model\Reports;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReportPointsSubscriber implements EventSubscriberInterface
{
    /**
     * @var PointsManager
     */
    protected $pointsManager;

    public function __construct(PointsManager $pointsManager)
    {
        $this->pointsManager = $pointsManager;
    }

    public function onModelInsert(ModelInsertEvent $event)
    {
        $model = $event->getModel();

        if (!$model instanceof Reports) {
            return;
        }

        $this->pointsManager->addPoints('report_points', 'You earned {points} {points_name} for submitting a report');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.insert' => 'onModelInsert'
        ];
    }
}
