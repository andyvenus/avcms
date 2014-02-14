<?php
/**
 * User: Andy
 * Date: 12/02/2014
 * Time: 14:38
 */

namespace AVCMS\Users\Model;

use AVCMS\Core\Model\Model;

class Groups extends Model
{
    public function getTable()
    {
        return 'groups';
    }

    public function getSingular()
    {
        return 'group';
    }

    public function getEntity()
    {
        return 'AVCMS\Users\Model\Group';
    }
}