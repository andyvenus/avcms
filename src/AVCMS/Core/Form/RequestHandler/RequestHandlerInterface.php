<?php
/**
 * User: Andy
 * Date: 04/02/2014
 * Time: 12:28
 */
namespace AVCMS\Core\Form\RequestHandler;

use AVCMS\Core\Form\FormHandler;

interface RequestHandlerInterface
{
    /**
     * @param $form_handler
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return array
     * @throws \Exception
     */
    public function handleRequest(FormHandler $form_handler, $request);
}