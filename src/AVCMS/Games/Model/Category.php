<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Entity;

class Category extends Entity {

    public function setName($value)
    {
        $this->set('name', $value);
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function setDescription($value)
    {
        $this->set('description', $value);
    }

    public function getDescription()
    {
        return $this->get('description');
    }

}