<?php

namespace AVCMS\Bundles\PrivateMessages\Model;

use AV\Model\Model;
use AV\Model\QueryBuilder\QueryBuilderHandler;
use AVCMS\Bundles\Users\Model\Users;

class PrivateMessages extends Model
{
    public function getTable()
    {
        return 'messages';
    }

    public function getSingular()
    {
        return 'private_message';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\PrivateMessages\Model\PrivateMessage';
    }

    public function getUserMessages($userId, Users $users = null)
    {
        $query = $this->query()->where('recipient_id', $userId);

        $this->joinUsers($query, $users);

        return $query->orderBy('id', 'desc')->get();
    }

    public function getMessage($recipientId, $messageId, Users $users = null)
    {
        $query = $this->query()->where('recipient_id', $recipientId)->where('messages.id', $messageId);

        $this->joinUsers($query, $users);

        return $query->first();
    }

    public function markMessageRead(PrivateMessage $message)
    {
        if (!$message->getRead()) {
            $this->query()->where('id', $message->getId())->update(['read' => 1]);

            $message->setRead(1);
        }
    }

    private function joinUsers(QueryBuilderHandler $query, Users $users = null)
    {
        if ($users) {
            $query->modelJoin($users, ['username', 'slug', 'avatar'], 'left', null, 'sender_id', '=', 'sender.id', 'sender');
        }
    }
}
