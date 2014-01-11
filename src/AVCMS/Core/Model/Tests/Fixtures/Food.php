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
    protected $table = 'food';

    protected $singular = 'food_item';

    protected $entity = 'AVCMS\Core\Model\Tests\Fixtures\FoodItem';
}