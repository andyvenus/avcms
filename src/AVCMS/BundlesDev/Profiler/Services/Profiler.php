<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:22
 */

namespace AVCMS\BundlesDev\Profiler\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector;

class Profiler implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('profiler', 'Symfony\Component\HttpKernel\Profiler\Profiler')
            ->setArguments(array(new Reference('profiler.file_storage')))
            ->addMethodCall('add', array(new Reference('profiler.memory')))
            ->addMethodCall('add', array(new Reference('profiler.time')))
            ->addMethodCall('add', array(new Reference('profiler.translations')))
            ->addMethodCall('add', array(new Reference('profiler.request')))
        ;

        $container->register('profiler.request', 'Symfony\Component\HttpKernel\DataCollector\RequestDataCollector')
            ->addTag('event.subscriber')
        ;

        $container->register('profiler.translations', 'AVCMS\BundlesDev\Profiler\Profiler\TranslationsDataCollector')
            ->setArguments(array(new Reference('translator')))
        ;

        $container->register('profiler.time', 'Symfony\Component\HttpKernel\DataCollector\TimeDataCollector')
            ->setArguments(array(null, new Reference('debug.stopwatch')))
        ;

        $container->register('profiler.memory', 'Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector');

        $container->register('profiler.file_storage', 'Symfony\Component\HttpKernel\Profiler\FileProfilerStorage')
            ->setArguments(array("file:cache/profiler"))
        ;

        $container->register('listener.profiler', 'Symfony\Component\HttpKernel\EventListener\ProfilerListener')
            ->setArguments(array(new Reference('profiler'), new Reference('request_matcher')))
            ->addTag('event.subscriber')
        ;

        $container->register('listener.dev_bar', 'AVCMS\BundlesDev\Profiler\Events\DevBarListener')
            ->setArguments(array(new Reference('twig')))
            ->addTag('event.subscriber')
        ;

        $container->register('debug.stopwatch', 'Symfony\Component\Stopwatch\Stopwatch');

        // Replace the event dispatcher with a traceable dispatcher

        $container->register('traceable_dispatcher', 'Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher')
            ->setArguments(array(new Reference('traceable_dispatcher.inner'), new Reference('debug.stopwatch')))
            ->setPublic(false)
            ->setDecoratedService('dispatcher')
        ;
    }
}