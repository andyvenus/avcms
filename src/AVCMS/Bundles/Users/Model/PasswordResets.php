<?php

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Model;

class PasswordResets extends Model
{
    public function getTable()
    {
        return 'password_resets';
    }

    public function getSingular()
    {
        return 'password_reset';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Users\Model\PasswordReset';
    }

    public function userHasRecentReset($userId, $recentTime = 900)
    {
        $query = $this->query()->where('user_id', $userId)->where('generated', '>', time() - $recentTime)->count();

        if ($query > 0) {
            return true;
        }

        return false;
    }

    public function findReset($userId, $code)
    {
        return $this->query()->where('user_id', $userId)->where('code', $code)->first();
    }

    public function deleteUserResets($userId)
    {
        $this->query()->where('user_id', $userId)->delete();
    }
}