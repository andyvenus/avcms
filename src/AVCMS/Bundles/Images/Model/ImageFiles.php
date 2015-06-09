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

    /**
     * @param $collectionId
     * @return ImageFile[]
     */
    public function getImageFiles($collectionId)
    {
        $files = $this->query()->where('image_id', $collectionId)->get();
        return ($files ? $files : []);
    }

    public function getImageFile($collectionId, $fileId)
    {
        return $this->query()->where('image_id', $collectionId)->where('id', $fileId)->first();
    }
}
