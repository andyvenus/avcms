<?php
/**
 * User: Andy
 * Date: 23/03/2014
 * Time: 11:58
 */

namespace AVCMS\Bundles\UsersBase\Model;

use AVCMS\Core\Model\Entity;

class GroupPermission extends Entity
{
    public function setGroup($value)
    {
        $this->set('group', $value);
    }

    public function getGroup() {
        return $this->get('group');
    }

    public function setName($value)
    {
        $this->set('name', $value);
    }

    public function getName() {
        return $this->get('name');
    }

    public function setType($value)
    {
        $this->set('type', $value);
    }

    public function getType() {
        return $this->get('type');
    }

    public function setValue($value)
    {
        $this->set('value', $value);
    }

    public function getValue() {
        return $this->get('value');
    }
} 