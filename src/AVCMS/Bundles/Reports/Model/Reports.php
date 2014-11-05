<?php

namespace AVCMS\Bundles\Reports\Model;

use AV\Model\Model;

class Reports extends Model
{
    protected $finder = 'AVCMS\Bundles\Reports\Model\ReportsFinder';

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