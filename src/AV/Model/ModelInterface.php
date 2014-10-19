<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 14:55
 */

namespace AV\Model;


interface ModelInterface {
    public function getTable();

    public function getSingular();

    public function getEntity();
} 