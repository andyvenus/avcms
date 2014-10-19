<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 15:34
 */

namespace AV\Model\Event;

use AV\Model\Entity;
use Symfony\Component\EventDispatcher\Event;

class ModelInsertEvent extends Event
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
} 