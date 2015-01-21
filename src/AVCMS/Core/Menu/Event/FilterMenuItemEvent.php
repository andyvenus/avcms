<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:17
 */

namespace AVCMS\Core\Menu\Event;

use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;
use Symfony\Component\EventDispatcher\Event;

class FilterMenuItemEvent extends Event
{
    private $menuItem;

    private $menuItemConfig;

    public function __construct(MenuItem $menuItem, MenuItemConfigInterface $menuItemConfig)
    {
        $this->menuItem = $menuItem;
        $this->menuItemConfig = $menuItemConfig;
    }

    public function getMenuItem()
    {
        return $this->menuItem;
    }

    public function getMenuItemConfig()
    {
        return $this->menuItemConfig;
    }
}
