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
    public function setId($id)
    {
        $this->set('id', $id);
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function setOwner($owner)
    {
        $this->set('owner', $owner);
    }

    public function getOwner()
    {
        return $this->get('owner');
    }

    public function setTranslatable($translatable)
    {
        $this->set('translatable', $translatable);
    }

    public function getTranslatable()
    {
        return $this->get('translatable');
    }

    public function setLabel($label)
    {
        $this->set('label', $label);
    }

    public function getLabel()
    {
        return $this->get('label');
    }

    public function setIcon($icon)
    {
        $this->set('icon', $icon);
    }

    public function getIcon()
    {
        return $this->get('icon');
    }

    public function setParent($parent)
    {
        $this->set('parent', $parent);
    }

    public function getParent()
    {
        return $this->get('parent');
    }

    public function setPermission($permission)
    {
        $this->set('permission', $permission);
    }

    public function getPermission()
    {
        return $this->get('permission');
    }

    public function setUrl($url)
    {
        $this->set('url', $url);
    }

    public function getUrl()
    {
        return $this->get('url');
    }
}
