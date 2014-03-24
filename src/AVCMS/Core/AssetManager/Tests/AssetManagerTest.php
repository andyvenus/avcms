<?php
/**
 * User: Andy
 * Date: 16/03/2014
 * Time: 15:53
 */

namespace AVCMS\Core\AssetManager\Tests;


use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetManager;

class AssetManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AssetManager
     */
    protected $asset_manager;

    protected $javascript_assets;

    protected $css_assets;

    public function setUp()
    {
        $mock_bundle = $this->getMockBuilder('\AVCMS\Core\Bundle\Bundle')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_bundle_manager = $this->getMockBuilder('\AVCMS\Core\BundleManager\BundleManager')
        ->disableOriginalConstructor()
        ->getMock();

        $mock_bundle_manager->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue(array('MockBundle' => array('instance' => $mock_bundle))));

        $mock_bundle_manager->expects($this->any())
            ->method('bundlesInitialized')
            ->will($this->returnValue(true));

        $this->asset_manager = new AssetManager($mock_bundle_manager);

        $this->javascript_assets[0] = new BundleFileAsset('MockBundle', 'javascript', 'one.js');
        $this->javascript_assets[1] = new BundleFileAsset('MockBundle', 'javascript', 'two.js');

        $this->css_assets[0] = new BundleFileAsset('MockBundle', 'css', 'one.css');
        $this->css_assets[1] = new BundleFileAsset('MockBundle', 'css', 'two.css');
    }

    protected function addStandardAssets()
    {
        $this->asset_manager->addJavascript($this->javascript_assets[0], AssetManager::FRONTEND);
        $this->asset_manager->addJavascript($this->javascript_assets[1], AssetManager::SHARED);

        $this->asset_manager->addCSS($this->css_assets[0], AssetManager::FRONTEND);
        $this->asset_manager->addCSS($this->css_assets[1], AssetManager::FRONTEND);
    }

    public function testEnvironments()
    {
        $this->addStandardAssets();

        $js_assets = $this->asset_manager->getJavaScript();
        $css_assets = $this->asset_manager->getCSS();

        $this->assertEquals(1, count($js_assets[AssetManager::FRONTEND]));
        $this->assertEquals(1, count($js_assets[AssetManager::SHARED]));
        $this->assertEquals(2, count($css_assets[AssetManager::FRONTEND]));
    }

    public function testRemoveBundleAsset()
    {
        $this->addStandardAssets();

        $this->asset_manager->removeBundleAsset('MockBundle', 'css', 'one.css');

        $js_assets = $this->asset_manager->getJavaScript();

        $this->assertEquals(1, count($js_assets[AssetManager::FRONTEND]));
    }

    public function testInvalidTypeException()
    {
        $this->setExpectedException('\Exception', "Invalid asset type 'invalidtype'. Only 'css' or 'javascript' are valid");

        $this->asset_manager->removeBundleAsset('MockBundle', 'invalidtype', 'example.js');
    }
}
 