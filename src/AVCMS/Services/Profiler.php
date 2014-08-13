<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:22
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector;

class Profiler implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('profiler', 'Symfony\Component\HttpKernel\Profiler\Profiler')
            ->setArguments(array(new Reference('profiler.file_storage')))
            ->addMethodCall('add', array(new MemoryDataCollector()))
            ->addMethodCall('add', array(new Reference('profiler.time')))
            ->addMethodCall('add', array(new Reference('profiler.translations')))
            ->addMethodCall('add', array(new Reference('profiler.request')))
        ;

        $container->register('profiler.request', 'Symfony\Component\HttpKernel\DataCollector\RequestDataCollector')
            ->addTag('event.subscriber')
        ;

        $container->register('profiler.translations', 'AVCMS\Core\Profiler\TranslationsDataCollector')
            ->setArguments(array(new Reference('translator')))
        ;

        $container->register('profiler.time', 'Symfony\Component\HttpKernel\DataCollector\TimeDataCollector')
            ->setArguments(array(null, new Reference('debug.stopwatch')))
        ;

        $container->register('profiler.file_storage', 'Symfony\Component\HttpKernel\Profiler\FileProfilerStorage')
            ->setArguments(array("file:".__DIR__."/../../../cache/profiler"))
        ;

        $container->register('listener.profiler', 'Symfony\Component\HttpKernel\EventListener\ProfilerListener')
            ->setArguments(array(new Reference('profiler'), new RequestMatcher))
            ->addTag('event.subscriber')
        ;
    }
}