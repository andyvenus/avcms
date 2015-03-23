<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:37
 */

namespace AVCMS\Bundles\Games\EventSubscriber;

use AVCMS\Bundles\Games\Model\GameSubmissions;
use AVCMS\Bundles\Reports\Model\Reports;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GameSubmissionsMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reports
     */
    private $gameSubmissions;

    public function __construct(GameSubmissions $gameSubmissions)
    {
        $this->gameSubmissions = $gameSubmissions;
    }

    public function addSubmissionsCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'game_submissions_admin_home') {
            $menuItem = $event->getMenuItem();

            $totalGameSubmissions = $this->gameSubmissions->query()->count();

            if ($totalGameSubmissions > 0) {
                $menuItem->badge = $totalGameSubmissions;
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
