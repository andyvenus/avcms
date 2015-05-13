<?php

namespace AVCMS\Bundles\Images\Model;

use AV\Model\Model;

class ImageFiles extends Model
{
    public function getTable()
    {
        return 'image_files';
    }

    public function getSingular()
    {
        return 'image_file';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Images\Model\ImageFile';
    }

    public function getImageFiles($imageId)
    {
        $files = $this->query()->where('image_id', $imageId)->get();
        return ($files ? $files : []);
    }
}
