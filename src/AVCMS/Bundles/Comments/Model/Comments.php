<?php

namespace AVCMS\Bundles\Comments\Model;

use AV\Model\Model;

class Comments extends Model
{
    protected $finder = 'AVCMS\Bundles\Comments\Model\CommentsFinder';

    public function getTable()
    {
        return 'comments';
    }

    public function getSingular()
    {
        return 'comment';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Comments\Model\Comment';
    }

    public function getComments($contentType, $contentId, Model $usersModel, $perPage = null, $page = 1, $replies = false)
    {
        $start = ($perPage * $page) - $perPage;

        $query = $this->query()
            ->where('content_type', $contentType)
            ->where('content_id', $contentId)
        ;

        if ($replies == false) {
            $query->where('thread', 0);
        }


        if ($perPage !== null && is_numeric($perPage)) {
            $query->offset($start)->limit($perPage);
        }

        $query->modelJoin($usersModel, ['id', 'username', 'slug', 'avatar']);

        return $query->get();
    }

    public function getReplies($commentId, Model $usersModel, $perPage = null, $page = 1)
    {
        $start = ($perPage * $page) - $perPage;

        $query = $this->query()->where('thread', $commentId);

        if ($perPage !== null && is_numeric($perPage)) {
            $query->offset($start)->limit($perPage);
        }

        $query->modelJoin($usersModel, ['id', 'username', 'slug', 'avatar']);

        return $query->get();
    }

    public function updateReplies($commentId)
    {
        $replies = $this->query()->where('thread', $commentId)->count();

        $this->query()->where('id', $commentId)->update(['replies' => $replies]);
    }

    public function getContentCommentCount($contentType, $contentId)
    {
        return $this->query()->where('content_type', $contentType)->where('content_id', $contentId)->count();
    }
}
