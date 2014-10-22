<?php
/**
 * User: Andy
 * Date: 18/10/14
 * Time: 15:07
 */

namespace AVCMS\Installer\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class InstallerController extends Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function installerHomeAction()
    {
        //$this->model('Users');

        return new Response('Hello world');
    }
}