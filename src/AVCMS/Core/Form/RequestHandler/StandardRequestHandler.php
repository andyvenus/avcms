<?php
/**
 * User: Andy
 * Date: 03/02/2014
 * Time: 15:31
 */

namespace AVCMS\Core\Form\RequestHandler;

use AVCMS\Core\Form\FormHandler;

class StandardRequestHandler implements RequestHandlerInterface {
    /**
     * @param $form_handler
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return array
     * @throws \Exception
     */
    public function handleRequest(FormHandler $form_handler, $request)
    {
        if ($form_handler->getMethod() == "GET") {
            return $_GET;
        }
        else {
            if ($form_handler->hasFieldOfType('file')) {
                return array_merge_recursive($_POST, $_FILES);
            }
            else {
                return $_POST;
            }
        }
    }
} 