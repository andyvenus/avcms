<?php
/**
 * User: Andy
 * Date: 05/12/14
 * Time: 14:59
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class MiscServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('site_offline_handler', 'AVCMS\Core\Security\SiteOfflineHandler')
            ->setArguments([new Reference('auth.access_denied_handler')])
            ->addTag('event.listener', ['event' => KernelEvents::EXCEPTION, 'method' => 'onKernelException', 'priority' => 100])
        ;
    }
}