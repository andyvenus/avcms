<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:56
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Developer implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('listener.dev_bar', 'AVCMS\BundlesDev\Profiler\Events\DevBarListener')
            ->setArguments(array(new Reference('twig')))
            ->addTag('event.subscriber')
        ;

        $container->register('debug.stopwatch', 'Symfony\Component\Stopwatch\Stopwatch');

        $container->register('traceable_dispatcher', 'Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher')
            ->setArguments(array(new Reference('traceable_dispatcher.inner'), new Reference('debug.stopwatch')))
            ->setPublic(false)
            ->setDecoratedService('dispatcher')
        ;
    }
} 