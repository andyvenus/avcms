<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;

class EmailValidationKeys extends Model
{
    public function getTable()
    {
        return 'email_validations';
    }

    public function getSingular()
    {
        return 'email_validation_key';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Users\Model\EmailValidationKey';
    }

    public function isValidKey($userId, $key)
    {
        return $this->query()->where('user_id', $userId)->where('code', $key)->count();
    }

    public function deleteUserKey($userId)
    {
        $this->query()->where('user_id', $userId)->delete();
    }
}