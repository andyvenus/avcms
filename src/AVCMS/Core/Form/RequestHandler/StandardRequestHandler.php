<?php
/**
 * User: Andy
 * Date: 03/02/2014
 * Time: 15:31
 */

namespace AVCMS\Core\Form\RequestHandler;

use AVCMS\Core\Form\FormHandler;

/**
 * Class StandardRequestHandler
 * @package AVCMS\Core\Form\RequestHandler
 *
 * Reads the request data from the global variables $_GET, $_POST & $_FILES
 */
class StandardRequestHandler implements RequestHandlerInterface
{
    /**
     * @param $form_handler
     * @param $request null Unused, global vars used to get data
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