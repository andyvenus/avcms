<?php
/**
 * User: Andy
 * Date: 12/10/2014
 * Time: 14:18
 */

namespace AVCMS\BundlesDev\DevTools\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class DevToolsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('dev_tool.bundle_translation_collector', 'AVCMS\BundlesDev\DevTools\Listener\BundleTranslationsCollector')
            ->setArguments([new Reference('translator'), new Reference('bundle_manager'), '%root_dir%', true])
            ->addTag('event.listener', ['event' => KernelEvents::TERMINATE, 'method' => 'onTerminate'])
        ;
    }
}
