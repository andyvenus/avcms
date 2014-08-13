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
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'jquery.js'), AssetManager::SHARED, 90))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'bootstrap.min.js')))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'bootstrap-markdown.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'markdown.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'to-markdown.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'select2.min.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'jquery.history.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'jquery.nanoscroller.min.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'admin/admin_navigation.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'admin/admin_browser.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'admin/admin_misc.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'avcms_form.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('css', 'bootstrap-datetimepicker.css'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'bootstrap-datetimepicker.min.js'), 'admin'))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'moment.min.js'), 'admin', 70))
            ->addMethodCall('add', array(new AppFileAsset('javascript', 'avcms_config.js'), AssetManager::SHARED, 80))
        ;
    }
}