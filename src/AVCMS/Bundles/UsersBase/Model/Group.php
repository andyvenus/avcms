<?php
/**
 * User: Andy
 * Date: 12/02/2014
 * Time: 14:41
 */

namespace AVCMS\Bundles\UsersBase\Model;

use AVCMS\Core\Model\Entity;

class Group extends Entity
{
    public function setName($value)
    {
        $this->setData('name', $value);
    }

    public function getName()
    {
        return $this->data('name');
    }
}