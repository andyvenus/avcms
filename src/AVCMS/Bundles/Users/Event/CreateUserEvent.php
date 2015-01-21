<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 11:21
 */

namespace AVCMS\Bundles\Users\Event;

use AVCMS\Bundles\Users\Model\User;
use Symfony\Component\EventDispatcher\Event;

class CreateUserEvent extends Event
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
