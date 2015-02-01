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
    private $menuManager;

    public function __construct(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
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

    public function getMenuItems($menuId)
    {
        return $this->menuManager->getMenuItems($menuId);
    }

    public function getName()
    {
        return 'avcms_menu_manager';
    }
}
