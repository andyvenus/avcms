<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:37
 */

namespace AVCMS\Bundles\Wallpapers\EventSubscriber;

use AVCMS\Bundles\Reports\Model\Reports;
use AVCMS\Bundles\Wallpapers\Model\WallpaperSubmissions;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WallpaperSubmissionsMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reports
     */
    private $wallpaperSubmissions;

    public function __construct(WallpaperSubmissions $wallpaperSubmissions)
    {
        $this->wallpaperSubmissions = $wallpaperSubmissions;
    }

    public function addSubmissionsCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'wallpaper_submissions_admin_home') {
            $menuItem = $event->getMenuItem();

            $totalWallpaperSubmissions = $this->wallpaperSubmissions->query()->count();

            if ($totalWallpaperSubmissions > 0) {
                $menuItem->badge = $totalWallpaperSubmissions;
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
