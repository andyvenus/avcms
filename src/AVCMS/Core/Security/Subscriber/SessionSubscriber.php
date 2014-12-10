<?php
/**
 * User: Andy
 * Date: 22/09/2014
 * Time: 18:40
 */

namespace AVCMS\Core\Security\Subscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\EventListener\SessionListener;

class SessionSubscriber extends SessionListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getSession()
    {
        if (!$this->container->has('session')) {
            return null;
        }

        return $this->container->get('session');
    }
}