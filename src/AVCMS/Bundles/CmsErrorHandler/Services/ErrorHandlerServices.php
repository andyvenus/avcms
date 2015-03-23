<?php
/**
 * User: Andy
 * Date: 10/11/14
 * Time: 18:43
 */

namespace AVCMS\Bundles\CmsErrorHandler\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErrorHandlerServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
            ->setArguments(array('%error_controller%'))
            ->addTag('event.subscriber')
        ;
    }
}
