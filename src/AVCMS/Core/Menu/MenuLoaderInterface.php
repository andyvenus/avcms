<?php
/**
 * User: Andy
 * Date: 26/08/2014
 * Time: 12:21
 */

namespace AVCMS\Core\Menu;


interface MenuLoaderInterface {
    public function loadMenuItems(MenuManager $menu_manager);
} 