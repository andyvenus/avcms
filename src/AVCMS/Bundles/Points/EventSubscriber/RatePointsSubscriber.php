<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 12:03
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AV\Model\Event\ModelInsertEvent;
use AVCMS\Bundles\LikeDislike\Model\Ratings;
use AVCMS\Bundles\Points\PointsManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RatePointsSubscriber implements EventSubscriberInterface
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

        if (!$model instanceof Ratings) {
            return;
        }

        $this->pointsManager->addPoints('rating_points', 'You earned {points} {points_name} for submitting a rating');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.insert' => 'onModelInsert'
        ];
    }
}
