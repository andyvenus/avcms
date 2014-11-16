<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 15:34
 */

namespace AV\Model\Event;

use AV\Model\Entity;
use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class ModelInsertEvent extends Event
{
    protected $data;

    protected $model;

    public function __construct($data, Model $model)
    {
        $this->data = $data;
        $this->model = $model;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getModel()
    {
        return $this->model;
    }
} 