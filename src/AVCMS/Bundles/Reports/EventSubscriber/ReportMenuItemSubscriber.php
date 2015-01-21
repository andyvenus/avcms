<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:37
 */

namespace AVCMS\Bundles\Reports\EventSubscriber;

use AVCMS\Bundles\Reports\Model\Reports;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReportMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reports
     */
    private $reports;

    public function __construct(Reports $reports)
    {
        $this->reports = $reports;
    }

    public function addReportsCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'manage_reports') {
            $menuItem = $event->getMenuItem();

            $totalReports = $this->reports->query()->count();

            if ($totalReports > 0) {
                $menuItem->badge = $totalReports;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'menu_manager.filter_item' => ['addReportsCount']
        ];
    }
}
