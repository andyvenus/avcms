<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 11:38
 */

namespace AVCMS\Bundles\Tags\Model;

use AVCMS\Core\Model\Entity;

class Tag extends Entity
{
    public function setId($id)
    {
        $this->setData('id', $id);
    }

    public function getId()
    {
        return $this->data('id');
    }

    public function setName($name) {
        $this->setData('name', $name);
    }

    public function getName() {
        return $this->data('name');
    }
} 