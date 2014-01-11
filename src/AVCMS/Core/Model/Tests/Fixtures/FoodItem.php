<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 22:45
 */

namespace AVCMS\Core\Model\Tests\Fixtures;

use AVCMS\Core\Model\Entity;

class FoodItem extends Entity
{
    public function setName($value) {
        $this->setData('name', $value);
    }

    public function getName() {
        return $this->data('name');
    }
} 