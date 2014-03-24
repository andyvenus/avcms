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
        $this->setData('group', $value);
    }

    public function getGroup() {
        return $this->data('group');
    }

    public function setName($value)
    {
        $this->setData('name', $value);
    }

    public function getName() {
        return $this->data('name');
    }

    public function setType($value)
    {
        $this->setData('type', $value);
    }

    public function getType() {
        return $this->data('type');
    }

    public function setValue($value)
    {
        $this->setData('value', $value);
    }

    public function getValue() {
        return $this->data('value');
    }
} 