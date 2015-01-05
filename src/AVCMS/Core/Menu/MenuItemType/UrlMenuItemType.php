<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 11:43
 */

namespace AVCMS\Core\Menu\MenuItemType;

use AV\Form\FormBlueprint;
use AVCMS\Core\Menu\MenuItem;
use AVCMS\Core\Menu\MenuItemConfigInterface;

class UrlMenuItemType implements MenuItemTypeInterface
{
    public function getMenuItems(MenuItemConfigInterface $menuItemConfig)
    {
        $menuItem = new MenuItem();
        $menuItem->fromArray($menuItemConfig->toArray(), true);

        $menuItem->setUrl($menuItemConfig->getSetting('url'));

        return $menuItem;
    }

    public function getFormFields(FormBlueprint $form)
    {
        $form->add('settings[url]', 'text', [
            'label' => 'URL',
        ]);
    }

    public function getName()
    {
        return 'URL';
    }

    public function getDescription()
    {
        return 'A URL to any page on the web';
    }
}
