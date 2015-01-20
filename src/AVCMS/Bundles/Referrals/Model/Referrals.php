<?php

namespace AVCMS\Bundles\Referrals\Model;

use AV\Model\Model;

class Referrals extends Model
{
    public function getTable()
    {
        return 'referrals';
    }

    public function getSingular()
    {
        return 'referral';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Referrals\Model\Referral';
    }

    public function getUserReferral($userId)
    {
        return $this->query()->where('user_id', $userId)->where('type', 'user')->first();
    }
}
