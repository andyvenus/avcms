<?php

namespace AVCMS\Bundles\Friends\Model;

use AV\Model\Model;
use AVCMS\Bundles\Users\Model\Users;

class FriendRequests extends Model
{
    public function getTable()
    {
        return 'friend_requests';
    }

    public function getSingular()
    {
        return 'friend_request';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Friends\Model\FriendRequest';
    }

    public function getRequest($senderId, $receiverId)
    {
        return $this->query()->where('sender_id', $senderId)->where('receiver_id', $receiverId)->first();
    }

    public function getFriendRequests($userId, Users $users = null)
    {
        $query = $this->query()->where('receiver_id', $userId);

        if ($users) {
            $query->modelJoin($users, ['id', 'username', 'slug', 'avatar'], 'left', null, 'sender_id', '=', 'sender.id', 'sender');
        }

        return $query->get();
    }

    public function requestExists($senderId, $receiverId)
    {
        return $this->query()->where('sender_id', $senderId)->where('receiver_id', $receiverId)->count();
    }

    public function deleteRequest($senderId, $receiverId)
    {
        $this->query()->where('sender_id', $senderId)->where('receiver_id', $receiverId)->delete();
    }

    public function getRequestsCount($userId)
    {
        return $this->query()->where('receiver_id', $userId)->count();
    }
}
