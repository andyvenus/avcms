<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 20:09
 */

namespace AV\Model\Tests\Fixtures;

use AV\Model\Model;

class Food extends Model
{
    public function getTable()
    {
        return 'food';
    }

    public function getSingular()
    {
        return 'food_item';
    }

    public function getEntity()
    {
        return 'AV\Model\Tests\Fixtures\FoodItem';
    }
}