<?php
/**
 * User: Andy
 * Date: 04/02/2014
 * Time: 12:28
 */
namespace AV\Form\RequestHandler;

use AV\Form\FormHandler;

interface RequestHandlerInterface
{
    /**
     * Return an array of request data
     *
     * @param $formHandler
     * @param $request mixed
     * @return array
     */
    public function handleRequest(FormHandler $formHandler, $request);
}