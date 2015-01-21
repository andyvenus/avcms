<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 16:17
 */

namespace AVCMS\Core\Menu\Event;

use AVCMS\Core\Menu\MenuItem;
use Symfony\Component\EventDispatcher\Event;

class FilterMenuItemEvent extends Event
{
    private $menuItem;

    public function __construct(MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function getMenuItem()
    {
        return $this->menuItem;
    }
}
