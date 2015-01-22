<?php
/**
 * User: Andy
 * Date: 22/01/15
 * Time: 18:35
 */

namespace AVCMS\Bundles\Links\EventSubscriber;

use AVCMS\Bundles\Links\Model\Links;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LinksMenuItemSubscriber implements EventSubscriberInterface
{
    /**
     * @var Links
     */
    private $links;

    public function __construct(Links $links)
    {
        $this->links = $links;
    }

    public function addLinksCount(FilterMenuItemEvent $event)
    {
        $menuItemConfig = $event->getMenuItemConfig();

        if ($menuItemConfig->getType() === 'route' && $menuItemConfig->getSetting('route') === 'links_admin_home') {
            $menuItem = $event->getMenuItem();

            $unseenLinks = $this->links->query()->where('admin_seen', 0)->count();

            if ($unseenLinks > 0) {
                $menuItem->badge = $unseenLinks;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'menu_manager.filter_item' => ['addLinksCount']
        ];
    }
}
