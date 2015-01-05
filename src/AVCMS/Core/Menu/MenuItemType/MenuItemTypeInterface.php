<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 11:41
 */

namespace AVCMS\Core\Menu\MenuItemType;

use AV\Form\FormBlueprint;
use AVCMS\Core\Menu\MenuItemConfigInterface;

interface MenuItemTypeInterface
{
    public function getMenuItems(MenuItemConfigInterface $menuItemConfig);

    public function getFormFields(FormBlueprint $form);

    public function getName();
}
