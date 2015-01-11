<?php

namespace AVCMS\Bundles\Adverts\Model;

use AV\Model\Model;

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
        return 'AVCMS\Bundles\Adverts\Model\Advert';
    }
}