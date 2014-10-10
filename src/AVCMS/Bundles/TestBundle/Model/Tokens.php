<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Tokens extends Model
{
    public function getTable()
    {
        return 'sessions';
    }

    public function getSingular()
    {
        return 'token';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\Token';
    }
}