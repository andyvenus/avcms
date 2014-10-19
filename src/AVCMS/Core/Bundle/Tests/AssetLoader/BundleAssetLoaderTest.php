<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 12:00
 */

namespace AVCMS\Core\Bundle\Tests\AssetLoader;

use AVCMS\Core\Bundle\AssetLoader\BundleAssetLoader;
use AV\Kernel\Bundle\BundleConfig;
use PHPUnit_Framework_Assert;

class BundleAssetLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleAssetLoader()
    {
        $mockBundleManager = $this->getMockBuilder('AV\Kernel\Bundle\BundleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockBundleManager->expects($this->once())
            ->method('getBundleConfigs')
            ->willReturn(array(
                'bundleConfig' => new BundleConfig($mockBundleManager, array(
                    'assets' => array(
                        'my_file.js' => array()
                    )
                )
            )));

        $mockAssetManager = $this->getMockBuilder('AVCMS\Core\AssetManager\AssetManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockAssetManager->expects($this->once())
            ->method('add')
            ->will($this->returnCallback(function($class, $env, $priority) {
                PHPUnit_Framework_Assert::assertInstanceOf('AVCMS\Core\AssetManager\Asset\BundleFileAsset', $class);
                PHPUnit_Framework_Assert::assertEquals('shared', $env);
                PHPUnit_Framework_Assert::assertEquals(10, $priority);
            }));

        $bundleAssetLoader = new BundleAssetLoader($mockBundleManager);

        $bundleAssetLoader->loadAssets($mockAssetManager);
    }
}
 