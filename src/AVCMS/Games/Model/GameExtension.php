<?php
/**
 * User: Andy
 * Date: 19/02/2014
 * Time: 19:01
 */

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\ExtensionEntity;

class GameExtension extends ExtensionEntity
{
    public function setSomething($a) {
        $this->setData('testone__something', $a);
    }

    public function getSomething() {
        return $this->data('testone__something');
    }
} 