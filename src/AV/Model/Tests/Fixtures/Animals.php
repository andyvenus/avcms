<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 20:09
 */

namespace AV\Model\Tests\Fixtures;

use AV\Model\Model;

class Animals extends Model
{
    public function getTable()
    {
        return 'animals';
    }

    public function getSingular()
    {
        return 'animals';
    }

    public function getEntity()
    {
        return 'AV\Model\Tests\Fixtures\Animal';
    }
}