<?php

namespace AVCMS\Bundles\PrivateMessages\Model;

use AV\Model\Model;
use AV\Model\QueryBuilder\QueryBuilderHandler;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Bundles\Users\Model\Users;

class PrivateMessages extends Model
{
    /**
     * @var Users
     */
    private $users;

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

    public function setUsers(Users $users)
    {
        $this->users = $users;
    }

    public function getUserMessages($userId)
    {
        $query = $this->query()->where('recipient_id', $userId);

        $this->joinUsers($query);

        return $query->orderBy('id', 'desc')->get();
    }

    public function getMessage($recipientId, $messageId)
    {
        $query = $this->query()->where('recipient_id', $recipientId)->where('messages.id', $messageId);

        $this->joinUsers($query);

        return $query->first();
    }

    public function markMessageRead(PrivateMessage $message, User $user = null)
    {
        if (!$message->getRead()) {
            $this->query()->where('id', $message->getId())->update(['read' => 1]);

            $message->setRead(1);

            $this->updateMessageCount($message->getRecipientId());

            if ($user) {
                $user->messages->setTotalUnread($user->messages->getTotalUnread() - 1);
            }
        }
    }

    public function toggleRead($messageIds, $recipientId, $readStatus = 1)
    {
        $messageIds = (array) $messageIds;

        $this->query()
            ->where('recipient_id', $recipientId)
            ->whereIn('id', $messageIds)
            ->update(['read' => $readStatus]);

        return $this->updateMessageCount($recipientId);
    }

    public function deleteMessages($messageIds, $recipientId)
    {
        $this->query()
            ->where('recipient_id', $recipientId)
            ->whereIn('id', $messageIds)
            ->delete();

        return $this->updateMessageCount($recipientId);
    }

    private function joinUsers(QueryBuilderHandler $query)
    {
        if (isset($this->users)) {
            $query->modelJoin($this->users, ['username', 'slug', 'avatar'], 'left', null, 'sender_id', '=', 'sender.id', 'sender');
        }
    }

    private function updateMessageCount($recipientId)
    {
        if (!isset($this->users)) {
            throw new \Exception('The PrivateMessages model requires the Users model (set with setUsers)');
        }

        $unreadCount = $this->query()->where('read', 0)->where('recipient_id', $recipientId)->count();

        $this->users->query()->where('id', $recipientId)->update(['messages__total_unread' => $unreadCount]);

        return $unreadCount;
    }
}
