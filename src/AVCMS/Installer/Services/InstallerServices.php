<?php
/**
 * User: Andy
 * Date: 18/10/14
 * Time: 15:10
 */

namespace AVCMS\Installer\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InstallerServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        // Override
        $container->register('router', 'Symfony\Component\Routing\Router')
            ->setArguments(array(new Reference('router.loader.yaml'), 'routes.yml'))
        ;

        // Override
        $container->register('resolver', 'AV\Controller\ControllerResolver')
            ->setArguments(array(new Reference('service_container')))
        ;

        // Override
        $container->register('app_config.file_locator', 'Symfony\Component\Config\FileLocator')
            ->setArguments(array('src/AVCMS/Installer/config'))
        ;

        //OR
        $container->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener');

        //OR
        $container->register('model_factory', 'AV\Model\ModelFactory')
            ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher')))
        ;
    }
}