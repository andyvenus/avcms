<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 22:45
 */

namespace AV\Model\Tests\Fixtures;

use AV\Model\Entity;

class FoodItem extends Entity
{
    public function setName($value) {
        $this->set('name', $value);
    }

    public function getName() {
        return $this->get('name');
    }
} 