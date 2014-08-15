<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 13:03
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Services\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Settings implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('settings_manager', 'AVCMS\Core\SettingsManager\SettingsManager')
            ->setArguments(array(new Reference('settings_model')))
        ;

        $container->register('settings_model', 'AVCMS\Bundles\CmsFoundation\Model\Settings')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\Settings'))
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;
    }
}