<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 12:09
 */

namespace AVCMS\Core\Menu;

interface MenuItemConfigInterface
{
    public function getId();

    public function getMenu();

    public function getType();

    public function setLabel($label);

    public function getLabel();

    public function getOwner();

    public function getTranslatable();

    public function getIcon();

    public function getParent();

    public function getPermission();

    public function getSettings();

    public function getSetting($setting);
}
