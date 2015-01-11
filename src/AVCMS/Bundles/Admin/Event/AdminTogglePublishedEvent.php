<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 11:07
 */

namespace AVCMS\Bundles\Admin\Event;

use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class AdminTogglePublishedEvent extends Event
{
    protected $request;

    protected $model;

    protected $column;

    public function __construct(Request $request, Model $model, $column)
    {
        $this->request = $request;
        $this->model = $model;
        $this->column = $column;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getColumn()
    {
        return $this->column;
    }
}
