<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 15:34
 */

namespace AVCMS\Core\Model\Event;

use AVCMS\Core\Model\Entity;
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