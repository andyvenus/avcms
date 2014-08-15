<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:40
 */

namespace AVCMS\Services;

use AVCMS\Core\AssetManager\Asset\AppFileAsset;
use AVCMS\Core\AssetManager\AssetManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Assets implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('asset_manager', 'AVCMS\Core\AssetManager\AssetManager')
            ->setArguments(array(new Reference('bundle_manager')))
            ->addMethodCall('addAppAsset', array('jquery.js', 'javascript', AssetManager::SHARED, 90))
            ->addMethodCall('addAppAsset', array('bootstrap.min.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('bootstrap-markdown.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('markdown.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('to-markdown.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('select2.min.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('bootstrap.min.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('jquery.history.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('jquery.nanoscroller.min.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('admin/admin_navigation.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('admin/admin_browser.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('admin/admin_misc.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('avcms_form.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('bootstrap-datetimepicker.min.js', 'javascript', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('bootstrap-datetimepicker.css', 'css', AssetManager::ADMIN))
            ->addMethodCall('addAppAsset', array('moment.min.js', 'javascript', AssetManager::ADMIN, 70))
            ->addMethodCall('addAppAsset', array('avcms_config.js', 'javascript', AssetManager::SHARED, 80))
        ;
    }
}