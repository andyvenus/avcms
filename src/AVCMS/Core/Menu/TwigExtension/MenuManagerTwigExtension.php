<?php
/**
 * User: Andy
 * Date: 02/09/2014
 * Time: 12:55
 */

namespace AVCMS\Core\Menu\TwigExtension;

use AVCMS\Core\Menu\MenuManager;

class MenuManagerTwigExtension extends \Twig_Extension
{
    public function __construct(MenuManager $menu_manager)
    {
        $this->menu_manager = $menu_manager;
    }

    public function getFunctions()
    {
        return array(
            'menu' => new \Twig_SimpleFunction(
                'menu',
                array($this, 'getMenuItems')
            )
        );
    }

    public function getMenuItems($menu_id)
    {
        return $this->menu_manager->getMenuItems($menu_id);
    }

    public function getName()
    {
        return 'avcms_menu_manager';
    }
}