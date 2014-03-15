<?php
/**
 * User: Andy
 * Date: 09/02/2014
 * Time: 19:19
 */

namespace AVCMS\Bundles\UsersBase\Model;

use AVCMS\Core\Model\Model;

class Users extends Model {
    public function getTable()
    {
        return 'users';
    }

    public function getSingular()
    {
        return 'user';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\UsersBase\Model\User';
    }

    public function getByUsernameOrEmail($identifier)
    {
        return $this->query()->where('username', $identifier)->orWhere('email', $identifier)->first();
    }
}