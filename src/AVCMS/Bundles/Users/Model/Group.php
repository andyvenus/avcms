<?php
/**
 * User: Andy
 * Date: 12/02/2014
 * Time: 14:41
 */

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Entity;

class Group extends Entity
{
    public function setName($value)
    {
        $this->set('name', $value);
    }

    public function getName()
    {
        return $this->get('name');
    }
}