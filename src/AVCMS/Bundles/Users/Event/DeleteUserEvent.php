<?php
/**
 * User: Andy
 * Date: 10/10/2014
 * Time: 13:53
 */

namespace AVCMS\Bundles\Users\Event;

use Symfony\Component\EventDispatcher\Event;

class DeleteUserEvent extends Event
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
} 