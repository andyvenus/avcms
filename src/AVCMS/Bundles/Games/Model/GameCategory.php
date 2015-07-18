<?php

namespace AVCMS\Bundles\Games\Model;

use AVCMS\Bundles\Categories\Model\Category;

class GameCategory extends Category
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
