<?php

namespace AVCMS\Bundles\Videos\Model;

use AVCMS\Bundles\Categories\Model\Category;

class VideoCategory extends Category
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
