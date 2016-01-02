<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:37
 */

namespace AVCMS\Bundles\Videos\EventSubscriber;

use AVCMS\Bundles\Videos\Model\VideoSubmissions;
use AVCMS\Bundles\Reports\Model\Reports;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoSubmissionsMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reports
     */
    private $videoSubmissions;

    public function __construct(VideoSubmissions $videoSubmissions)
    {
        $this->videoSubmissions = $videoSubmissions;
    }

    public function addSubmissionsCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'video_submissions_admin_home') {
            $menuItem = $event->getMenuItem();

            $totalVideoSubmissions = $this->videoSubmissions->query()->count();

            if ($totalVideoSubmissions > 0) {
                $menuItem->badge = $totalVideoSubmissions;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'menu_manager.filter_item' => ['addSubmissionsCount']
        ];
    }
}
