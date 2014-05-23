<?php
/**
 * User: Andy
 * Date: 26/04/2014
 * Time: 13:52
 */

namespace AVCMS;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    public function exceptionAction(FlattenException $exception)
    {

        if ($exception->getStatusCode() == 404 || $exception->getClass() == 'AVCMS\Core\Security\PermissionsError') {
            return new Response($this->render('error.twig', array('error' => $exception)), $exception->getStatusCode());
        }

        else {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }
}