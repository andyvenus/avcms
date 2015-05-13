<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:37
 */

namespace AVCMS\Bundles\Images\EventSubscriber;

use AVCMS\Bundles\Images\Model\ImageSubmissions;
use AVCMS\Bundles\Reports\Model\Reports;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageSubmissionsMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reports
     */
    private $imageSubmissions;

    public function __construct(ImageSubmissions $imageSubmissions)
    {
        $this->imageSubmissions = $imageSubmissions;
    }

    public function addSubmissionsCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'image_submissions_admin_home') {
            $menuItem = $event->getMenuItem();

            $totalImageSubmissions = $this->imageSubmissions->query()->count();

            if ($totalImageSubmissions > 0) {
                $menuItem->badge = $totalImageSubmissions;
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
