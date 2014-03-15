<?php
/**
 * User: Andy
 * Date: 07/03/2014
 * Time: 13:29
 */

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Entity;

class CatJoin extends Entity
{
    public function getCow()
    {
        return $this->data('cow');
    }

    public function setCow($cow)
    {
        $this->setData('cow', $cow);
    }
} 