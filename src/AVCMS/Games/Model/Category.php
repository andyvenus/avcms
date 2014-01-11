<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Entity;

class Category extends Entity {

    public function setName($value)
    {
        $this->setData('name', $value);
    }

    public function getName()
    {
        return $this->data('name');
    }

}