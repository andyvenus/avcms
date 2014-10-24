<?php

namespace AVCMS\Bundles\Installer\Model;

use AV\Model\Model;

class Versions extends Model
{
    public function getTable()
    {
        return 'versions';
    }

    public function getSingular()
    {
        return 'version';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Installer\Model\Version';
    }

    public function getInstalledVersion($id, $type)
    {
        $version = $this->query()->where('id', $id)->where('type', $type)->first();

        if (!$version) {
            return 0;
        }

        return $version->getInstalledVersion();
    }

    public function setInstalledVersion($id, $versionNo, $type)
    {
        $this->query()->where('id', $id)->where('type', $type)->delete();

        $version = $this->newEntity();
        $version->setId($id);
        $version->setInstalledVersion($versionNo);
        $version->setType($type);

        $this->insert($version);
    }
}