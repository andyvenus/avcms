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

    public function getComments($contentType, $contentId, $usersModel, $perPage = null, $page = null)
    {
        if (!is_numeric($page)) {
            $page = 1;
        }

        $start = ($perPage * $page) - $perPage;

        $query = $this->query()
            ->where('content_type', $contentType)
            ->where('content_id', $contentId);


        if ($perPage !== null && is_numeric($perPage)) {
            $query->offset($start)->limit($perPage);
        }

        $query->modelJoin($usersModel, ['username', 'slug']);

        return $query->get();
    }
}