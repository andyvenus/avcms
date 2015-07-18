<?php

namespace AVCMS\Bundles\Images\Model;

use AV\Model\Entity;
use AVCMS\Bundles\Categories\Model\Category;

class ImageCategory extends Category
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
