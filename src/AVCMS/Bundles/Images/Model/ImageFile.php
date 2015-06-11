<?php

namespace AVCMS\Bundles\Images\Model;

use AV\Model\Entity;

class ImageFile extends Entity
{
    public function setCaption($value)
    {
        $this->set("caption", $value);
    }

    public function getCaption()
    {
        return $this->get("caption");
    }

    public function setUrl($value)
    {
        $this->set("url", $value);
    }

    public function getUrl()
    {
        return $this->get("url");
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setCollectionId($value)
    {
        $this->set("collection_id", $value);
    }

    public function getCollectionId()
    {
        return $this->get("collection_id");
    }

    public function setImportFolder($value)
    {
        $this->set("import_folder", $value);
    }

    public function getImportFolder()
    {
        return $this->get("import_folder");
    }
}
