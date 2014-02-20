<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 20:09
 */

namespace AVCMS\Core\Model\Tests\Fixtures;

use AVCMS\Core\Model\Model;

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
        return 'AVCMS\Core\Model\Tests\Fixtures\Animal';
    }
}