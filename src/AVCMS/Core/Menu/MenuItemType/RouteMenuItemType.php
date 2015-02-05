<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 11:43
 */

namespace AVCMS\Core\Menu\MenuItemType;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider\RouteChoicesProvider;
use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class RouteMenuItemType implements MenuItemTypeInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        $menuItem = new MenuItem();
        $menuItem->fromArray($menuItemConfig->toArray(), true);

        try {
            $menuItem->setUrl($this->router->generate($menuItemConfig->getSetting('route')));
        }
        catch (RouteNotFoundException $e) {
            $menuItem->setUrl('#route-'.$menuItemConfig->getSetting('route').'-not-found');
        }

        return $menuItem;
    }

    public function getFormFields(FormBlueprint $form)
    {
        $form->add('settings[route]', 'select', [
            'label' => 'Route',
            'choices_provider' => new RouteChoicesProvider($this->router),
            'choices_translate' => false
        ]);
    }

    public function getName()
    {
        return 'Route';
    }

    public function getDescription()
    {
        return 'A menu item that links to a page on your site';
    }
}
