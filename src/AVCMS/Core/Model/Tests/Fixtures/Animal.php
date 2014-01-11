<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 22:45
 */

namespace AVCMS\Core\Model\Tests\Fixtures;

use AVCMS\Core\Model\Entity;

class Animal extends Entity
{
    public function setId($value) {
        $this->setData('id', $value);
    }

    public function getId() {
        return $this->data('id');
    }

    public function setName($value) {
        $this->setData('name', $value);
    }

    public function getName() {
        return $this->data('name');
    }

    public function setDescription($value) {
        $this->setData('description', $value);
    }

    public function getDescription() {
        return $this->data('description');
    }

    public function setViews($value) {
        $this->setData('views', $value);
    }

    public function getViews() {
        return $this->data('views');
    }

    public function setFoodItemId($value) {
        $this->setData('food_item_id', $value);
    }

    public function getFoodItemId() {
        return $this->data('food_item_id');
    }
} 