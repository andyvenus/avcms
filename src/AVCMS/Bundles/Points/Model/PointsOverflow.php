<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\Points\Model;

use AV\Model\ExtensionEntity;

class PointsOverflow extends ExtensionEntity
{
    public function getPoints()
    {
        return $this->get('points');
    }

    public function setPoints($id)
    {
        $this->set('points', $id);
    }

    public function getPrefix()
    {
        return 'points';
    }

    public function __toString()
    {
        return (string) $this->get('points');
    }
}
