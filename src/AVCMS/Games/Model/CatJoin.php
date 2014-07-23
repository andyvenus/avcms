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
        return $this->get('cow');
    }

    public function setCow($cow)
    {
        $this->set('cow', $cow);
    }
} 