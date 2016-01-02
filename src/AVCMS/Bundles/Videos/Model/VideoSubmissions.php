<?php

namespace AVCMS\Bundles\Videos\Model;

use AV\Model\Model;

class VideoSubmissions extends Model
{
    public function getTable()
    {
        return 'video_submissions';
    }

    public function getSingular()
    {
        return 'video_submission';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Videos\Model\Video';
    }
}
