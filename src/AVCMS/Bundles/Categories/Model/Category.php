<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 12:10
 */

namespace AVCMS\Bundles\Categories\Model;

use AV\Model\Entity;

class Category extends Entity
{
    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function getParent()
    {
        return $this->get("parent");
    }

    public function setParent($value)
    {
        $this->set("parent", $value);
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function getSlug()
    {
        return $this->get("slug");
    }
}
