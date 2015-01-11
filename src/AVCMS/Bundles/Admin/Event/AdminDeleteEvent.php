<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 11:15
 */

namespace AVCMS\Bundles\Admin\Event;

use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class AdminDeleteEvent extends Event
{
    protected $request;

    protected $model;

    public function __construct(Request $request, Model $model)
    {
        $this->request = $request;
        $this->model = $model;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getModel()
    {
        return $this->model;
    }
}
