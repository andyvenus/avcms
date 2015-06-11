<?php

namespace AVCMS\Bundles\Images\Model;

use AV\Model\Model;

class ImageSubmissions extends Model
{
    public function getTable()
    {
        return 'image_submissions';
    }

    public function getSingular()
    {
        return 'image_submission';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Images\Model\ImageCollection';
    }
}
