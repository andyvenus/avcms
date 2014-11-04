<?php

namespace AVCMS\Bundles\Reports\Model;

use AV\Model\Model;

class Reports extends Model
{
    public function getTable()
    {
        return 'reports';
    }

    public function getSingular()
    {
        return 'report';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Reports\Model\Report';
    }
}