<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 15:25
 */

namespace AVCMS\Bundles\Installer\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InstallerServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('installer', 'AVCMS\Core\Installer\Installer')
            ->setArguments([new Reference('service_container'), new Reference('bundle_finder'), new Reference('installer.versions_model'), '%main_app_dir%'])
        ;

        $container->register('installer.versions_model', 'AVCMS\Bundles\Installer\Model\Versions')
            ->setArguments(array('AVCMS\Bundles\Installer\Model\Versions'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('bundle_finder', 'AVCMS\Core\Installer\InstallerBundleFinder');
    }
}