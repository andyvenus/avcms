<?php
/**
 * User: Andy
 * Date: 16/03/2014
 * Time: 15:53
 */

namespace AVCMS\Core\AssetManager\Tests;


use Assetic\Asset\FileAsset;
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
        $this->asset_manager->add($this->javascript_assets[0], AssetManager::FRONTEND);
        $this->asset_manager->add($this->javascript_assets[1], AssetManager::SHARED);

        $this->asset_manager->add($this->css_assets[0], AssetManager::FRONTEND);
        $this->asset_manager->add($this->css_assets[1], AssetManager::FRONTEND);
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

    public function testAddAsset()
    {
        $this->asset_manager->add($this->javascript_assets[0]);

        $js = $this->asset_manager->getJavaScript();

        $this->assertSame($this->javascript_assets[0], $js[AssetManager::SHARED][0]['asset']);

        //

        $this->asset_manager->add($this->css_assets[0]);

        $css = $this->asset_manager->getCSS();

        $this->assertSame($this->css_assets[0], $css[AssetManager::SHARED][0]['asset']);
    }

    public function testInvalidAssetType()
    {
        $this->setExpectedException('\Exception', 'Assets passed to the add() method must implement the getType method');

        $asset = new FileAsset('my_file');

        $this->asset_manager->add($asset);
    }

    public function testAddAssetGetTypeException()
    {
        $this->setExpectedException('\Exception', 'Assets passed to the add() method must implement the getType method');

        $asset = new FileAsset('test.js');
        $this->asset_manager->add($asset);
    }

    public function testRemoveBundleAsset()
    {
        $this->addStandardAssets();

        $this->assertTrue($this->asset_manager->removeBundleAsset('MockBundle', 'css', 'one.css'));

        $js_assets = $this->asset_manager->getJavaScript();

        $this->assertEquals(1, count($js_assets[AssetManager::FRONTEND]));
    }

    public function testInvalidTypeException()
    {
        $this->setExpectedException('\Exception', "Invalid asset type 'invalidtype'. Only 'css' or 'javascript' are valid");

        $this->asset_manager->removeBundleAsset('MockBundle', 'invalidtype', 'example.js');
    }

    public function testGenerateProductionAssets()
    {
        $writer = $this->getMockBuilder('Assetic\AssetWriter')
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->once())
            ->method('writeManagerAssets')
            ->with($this->isInstanceOf('Assetic\AssetManager'));

        $this->asset_manager->add($this->javascript_assets[0], AssetManager::ADMIN);
        $this->asset_manager->add($this->javascript_assets[1], AssetManager::SHARED);

        $this->asset_manager->generateProductionAssets($writer);
    }

    public function testGetDevAssetUrls()
    {
        $this->asset_manager->add($this->javascript_assets[0], AssetManager::ADMIN, 10);
        $this->asset_manager->add($this->javascript_assets[1], AssetManager::SHARED, 50);

        $assets = $this->asset_manager->getDevAssetUrls('javascript', AssetManager::ADMIN);

        $expected = array(
            "front.php/bundle_asset/MockBundle/javascript/two.js",
            "front.php/bundle_asset/MockBundle/javascript/one.js"
        );

        $this->assertCount(2, $assets);

        foreach ($assets as $asset) {
            $this->assertContains('.js', $asset);
        }
    }

    public function testInvalidAssetTypeException()
    {
        $this->setExpectedException('AVCMS\Core\AssetManager\Exception\AssetTypeException');

        $this->asset_manager->getDevAssetUrls('fake_type', AssetManager::SHARED);
    }
}