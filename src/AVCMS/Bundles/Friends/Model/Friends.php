<?php

namespace AVCMS\Bundles\Friends\Model;

use AV\Model\Model;
use AVCMS\Bundles\Users\Model\Users;

class Friends extends Model
{
    public function getTable()
    {
        return 'friends';
    }

    public function getSingular()
    {
        return 'friend';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Friends\Model\Friend';
    }

    public function getUsersFriends($userId, Users $users)
    {
        $friendsResult = $this->query()
            ->where('user1', $userId)
            ->orWhere('user2', $userId)
            ->get()
        ;

        $friendIds = [];
        foreach ($friendsResult as $friendResult) {
            if ($friendResult->getUser1() != $userId) {
                $friendIds[] = $friendResult->getUser1();
            }
            else {
                $friendIds[] = $friendResult->getUser2();
            }
        }

        if (empty($friendIds)) {
            return [];
        }

        return $users->query()->whereIn('id', $friendIds)->get();
    }

    public function removeFriendship($user1, $user2)
    {
        $this->query()->where(function($q) use ($user1, $user2) {
            $q->where('user1', $user1);
            $q->where('user2', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('user1', $user2);
            $q->where('user2', $user1);
        })
        ->delete();
    }

    public function friendshipExists($user1, $user2)
    {
        $exists = $this->query()->where(function($q) use ($user1, $user2) {
            $q->where('user1', $user1);
            $q->where('user2', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('user1', $user2);
            $q->where('user2', $user1);
        })
        ->count();

        return $exists ? true : false;
    }
}
