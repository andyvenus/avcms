<?php
/**
 * User: Andy
 * Date: 23/02/15
 * Time: 13:08
 */

namespace AVCMS\Bundles\PrivateMessages\Model;

use AV\Model\ExtensionEntity;

class MessagesOverflow extends ExtensionEntity
{
    public function getTotalUnread()
    {
        return $this->get('total_unread');
    }

    public function setTotalUnread($id)
    {
        $this->set('total_unread', $id);
    }

    public function getPrefix()
    {
        return 'messages';
    }
}
