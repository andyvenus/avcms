<?php
/**
 * User: Andy
 * Date: 03/02/2014
 * Time: 15:31
 */

namespace AVCMS\Core\Form\RequestHandler;

use AVCMS\Core\Form\FormHandler;

class SymfonyRequestHandler implements RequestHandlerInterface
{
    /**
     * @param $form_handler
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return array
     * @throws \Exception
     */
    public function handleRequest(FormHandler $form_handler, $request)
    {
        if (!is_a($request, 'Symfony\Component\HttpFoundation\Request')) {
            throw new \Exception("The form is set to use Symfony request objects but was not given one in the handleRequest method");
        }

        if ($form_handler->getMethod() == 'POST') {
            $param_bag = 'request';
        }
        else {
            $param_bag = 'query';
        }

        $params = $request->$param_bag->all();

        if ($form_handler->hasFieldOfType('file')) {
            $files = $request->files->all();

            $params = array_merge_recursive($params, $files);
        }

        return $params;
    }
} 