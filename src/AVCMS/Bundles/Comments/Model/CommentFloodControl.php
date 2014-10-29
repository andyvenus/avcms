<?php
/**
 * User: Andy
 * Date: 29/10/14
 * Time: 10:52
 */

namespace AVCMS\Bundles\Comments\Model;

use AV\Model\Model;

class CommentFloodControl extends Model
{
    public function getTable()
    {
        return 'comment_flood_control';
    }

    public function getSingular()
    {
        return 'comment_flood_control';
    }

    public function getEntity()
    {
        return null;
    }

    public function getLastCommentTime($user)
    {
        $floodControl = $this->query()->where('user_id', $user)->first(\PDO::FETCH_ASSOC);

        return (isset($floodControl['last_comment_time']) ? $floodControl['last_comment_time'] : 0);
    }

    public function setLastCommentTime($user, $time)
    {
        $this->query()->where('user_id', $user)->delete();

        $this->query()->insert(['user_id' => $user, 'last_comment_time' => $time]);
    }
}