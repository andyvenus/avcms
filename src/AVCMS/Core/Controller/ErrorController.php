<?php
/**
 * User: Andy
 * Date: 26/04/2014
 * Time: 13:52
 */

namespace AVCMS\Core\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    public function exceptionAction(FlattenException $exception)
    {
        if ($exception->getStatusCode() == 404 || $exception->getClass() == 'AVCMS\Core\Security\PermissionsError') {
            return new Response($this->render('error.twig', array('error' => $exception)), $exception->getStatusCode());
        }
        elseif($exception->getClass() == 'Symfony\Component\Security\Core\Exception\AccessDeniedException') {
            return new Response($this->render('error.twig', array('error' => $exception, 'code' => 403)), 403);
        }
        else {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }
}