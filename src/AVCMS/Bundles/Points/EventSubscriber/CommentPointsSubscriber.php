<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 12:03
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AV\Model\Event\ModelInsertEvent;
use AVCMS\Bundles\Comments\Model\Comments;
use AVCMS\Bundles\Points\PointsManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentPointsSubscriber implements EventSubscriberInterface
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

        if (!$model instanceof Comments) {
            return;
        }

        $this->pointsManager->addPoints('comments_points', 'You earned {points} {points_name} for submitting a comment');
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.insert' => 'onModelInsert'
        ];
    }
}
