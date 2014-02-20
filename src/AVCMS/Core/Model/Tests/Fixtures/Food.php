<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 20:09
 */

namespace AVCMS\Core\Model\Tests\Fixtures;

use AVCMS\Core\Model\Model;

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
        return 'AVCMS\Core\Model\Tests\Fixtures\FoodItem';
    }
}