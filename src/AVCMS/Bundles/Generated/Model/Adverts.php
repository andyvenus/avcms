<?php

namespace AVCMS\Bundles\Generated\Model;

use AVCMS\Core\Model\Model;

class Adverts extends Model
{
    public function getTable()
    {
        return 'adverts';
    }

    public function getSingular()
    {
        return 'advert';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Generated\Model\Advert';
    }
}