<?php

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Bundles\Categories\Model\Category;

class BlogCategory extends Category
{
    public function getDescription()
    {
        return $this->get("description");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }
}
