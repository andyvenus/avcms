<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:03
 */

namespace AV\Kernel\Events;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;

class KernelBootEvent extends Event
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }
} 
