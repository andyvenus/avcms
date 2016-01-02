<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 11:38
 */

namespace AVCMS\Bundles\Tags\Model;

use AV\Model\Entity;

class Tag extends Entity
{
    public function setId($id)
    {
        $this->set('id', $id);
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function setName($name) {
        $this->set('name', $name);
    }

    public function getName() {
        return $this->get('name');
    }

    public function getSlug()
    {
        return str_replace(' ', '-', $this->get('name'));
    }
} 
