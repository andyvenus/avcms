<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;

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
}