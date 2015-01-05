<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 11:45
 */

namespace AVCMS\Core\Menu;

use AV\Model\Entity;

class MenuItem extends Entity
{
    /**
     * @var MenuItemConfigInterface
     */
    protected $menuItemConfig;

    protected $url;

    public function __construct(MenuItemConfigInterface $menuItemConfig)
    {
        $this->menuItemConfig = $menuItemConfig;
    }

    public function getId()
    {
        return $this->menuItemConfig->getId();
    }

    public function getOwner()
    {
        return $this->menuItemConfig->getOwner();
    }

    public function getTranslatable()
    {
        return $this->menuItemConfig->getTranslatable();
    }

    public function getLabel()
    {
        return $this->menuItemConfig->getLabel();
    }

    public function getIcon()
    {
        return $this->menuItemConfig->getIcon();
    }

    public function getParent()
    {
        return $this->menuItemConfig->getParent();
    }

    public function getPermission()
    {
        return $this->menuItemConfig->getPermission();
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
