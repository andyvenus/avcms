<?php
/**
 * User: Andy
 * Date: 25/01/15
 * Time: 14:22
 */

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Finder;

class UserFinder extends Finder
{
    public function group($groupId)
    {
        if (!$groupId) {
            return;
        }

        $this->currentQuery->where('role_list', $groupId);
    }
}
