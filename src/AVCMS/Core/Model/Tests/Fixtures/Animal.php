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
        $this->set('id', $value);
    }

    public function getId() {
        return $this->get('id');
    }

    public function setName($value) {
        $this->set('name', $value);
    }

    public function getName() {
        return $this->get('name');
    }

    public function setDescription($value) {
        $this->set('description', $value);
    }

    public function getDescription() {
        return $this->get('description');
    }

    public function setViews($value) {
        $this->set('views', $value);
    }

    public function getViews() {
        return $this->get('views');
    }

    public function setFoodItemId($value) {
        $this->set('food_item_id', $value);
    }

    public function getFoodItemId() {
        return $this->get('food_item_id');
    }
} 