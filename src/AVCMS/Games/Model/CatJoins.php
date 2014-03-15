<?php
/**
 * User: Andy
 * Date: 07/03/2014
 * Time: 13:28
 */

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Model;

class CatJoins extends Model
{

    public function getTable()
    {
        return 'catjoins';
    }

    public function getSingular()
    {
        return 'catjoin';
    }

    public function getEntity()
    {
        return 'AVCMS\Games\Model\CatJoin';
    }
}