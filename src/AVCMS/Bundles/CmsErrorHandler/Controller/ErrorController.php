<?php
/**
 * User: Andy
 * Date: 26/04/2014
 * Time: 13:52
 */

namespace AVCMS\Bundles\CmsErrorHandler\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    public function exceptionAction(FlattenException $exception)
    {
        if($exception->getClass() == 'Symfony\Component\Security\Core\Exception\AccessDeniedException') {
            return new Response($this->render('@CmsErrorHandler/error.twig', array('dev_mode' => $this->container->getParameter('dev_mode'), 'error' => $exception, 'code' => 403)), 403);
        }
        elseif ($this->container->getParameter('dev_mode') === true) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
        else {
            return new Response($this->render('@CmsErrorHandler/error.twig', array('dev_mode' => $this->container->getParameter('dev_mode'), 'error' => $exception, 'code' => 500)), 500);
        }
    }
}