<?php
/**
 * User: Andy
 * Date: 03/02/2014
 * Time: 15:31
 */

namespace AV\Form\RequestHandler;

use AV\Form\FormHandler;

/**
 * Class SymfonyRequestHandler
 * @package AV\Form\RequestHandler
 *
 * Reads the request data from a Symfony Request object
 */
class SymfonyRequestHandler implements RequestHandlerInterface
{
    /**
     * @param $formHandler
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return array
     * @throws \Exception
     */
    public function handleRequest(FormHandler $formHandler, $request)
    {
        if (!is_a($request, 'Symfony\Component\HttpFoundation\Request')) {
            throw new \Exception("The form is set to use Symfony request objects but was not given one in the handleRequest method");
        }

        if ($formHandler->getMethod() == 'POST') {
            $paramBag = 'request';
        }
        else {
            $paramBag = 'query';
        }

        $params = $request->$paramBag->all();

        if ($formHandler->hasFieldOfType('file')) {
            $files = $request->files->all();

            $params = array_merge_recursive($params, $files);
        }

        return $params;
    }
} 