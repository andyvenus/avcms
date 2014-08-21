<?php
/**
 * User: Andy
 * Date: 19/08/2014
 * Time: 17:14
 */

namespace AVCMS\Core\Bundle\Tests;

use AVCMS\Core\Bundle\BundleManager;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Yaml;

class BundleManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AVCMS\Core\Bundle\BundleManager
     */
    private $bundle_manager;

    private $root;

    private $bundle_locations;

    public function setUp()
    {
        $this->root = vfsStream::setup('root', 0777);
        $this->root = vfsStream::create(array(
            'Bundles' => array(
                'FakeBundle' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array(
                                'name' => 'FakeBundle',
                                'namespace' => 'AVCMS\Core\Bundle\Tests\Resource',
                                'services' => array(
                                    'BundleServices'
                                ),
                                'user_settings' => array(
                                    'my_setting' => array(
                                        'name' => 'my_name'
                                    )
                                )
                            )
                        ),
                        'routes.yml' => Yaml::dump(array('my_route' => array('path' => '/path')))
                    )
                ),
                'FakeDevBundle' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array('name' => 'FakeDevBundle', 'namespace' => 'AVCMS\Core\Bundle\Tests\Resource'))
                    )
                )
            ),
            'cache' => array(),
            'config' => array(
                'bundles.yml' => Yaml::dump(array('FakeBundle' => array('enabled' => true, 'config' => array('model' => 'Example\Model')))),
                'bundles_dev.yml' => Yaml::dump(array('FakeDevBundle' => array('enabled' => true)))
            ),
            'BadBundles' => array(
                'config' => array(
                    'bundles.yml' => Yaml::dump(array('BadBundle' => array('enabled' => true))),
                ),
                'BadBundle' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array('name' => 'BadBundle', 'namespace' => 'Bad', 'services' => array('BadService')))
                    )
                )
            )
        ));

        $this->bundle_locations = array(vfsStream::url('root/Bundles'));
        $this->bundle_manager = new BundleManager($this->bundle_locations, vfsStream::url('root/config'), vfsStream::url('root/cache'));
    }

    public function tearDown()
    {
        file_put_contents(__DIR__.'/Resource/bundle_config.php', "<?php
            return array(
                'FakeBundle' => array(
                    'enabled' => true,
                    'directory' => 'vfs://root/Bundles/FakeBundle'
                )
            );"
        );
    }

    public function testInjectConfiguaration()
    {
        $config = $this->getMock('Symfony\Component\Config\Definition\ConfigurationInterface');
        $bundle_manager = new BundleManager(array(vfsStream::url('root/Bundles')), $config);
    }

    public function testSetDebug()
    {
        $this->assertFalse($this->bundle_manager->isDebug());

        $this->bundle_manager->setDebug(true);

        $this->assertTrue($this->bundle_manager->isDebug());
    }

    public function testLoadAppBundleConfigNotFresh()
    {
        $bm = $this->bundle_manager;

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $config_cache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(false));
        $config_cache->expects($this->once())->method('write')
            ->will($this->returnCallback(function($contents) {
                \PHPUnit_Framework_Assert::assertStringStartsWith('<?php', $contents);
                $config_array = eval(str_replace('<?php', '', $contents));
                \PHPUnit_Framework_Assert::assertArrayHasKey('FakeBundle', $config_array);
            }));

        $bm->setConfigCache($config_cache);
        $bm->loadAppBundleConfig();
    }

    public function testLoadAppBundleConfigFresh()
    {
        $bm = new BundleManager($this->bundle_locations, vfsStream::url('root/config'), __DIR__.'/Resource');

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $config_cache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(true));
        $config_cache->expects($this->never())->method('write');

        $bm->setConfigCache($config_cache);
        $config = $bm->loadAppBundleConfig();

        $this->assertArrayHasKey('FakeBundle', $config);
        $this->assertTrue($bm->cacheIsFresh());
    }

    public function testLoadAppBundleConfigNotFoundException()
    {
        $bm = new BundleManager(array(), 'not_exists');

        $this->setExpectedException('AVCMS\Core\Bundle\Exception\NotFoundException');

        $bm->loadAppBundleConfig();
    }

    public function testLoadAppBundleConfigDev()
    {
        $cache_dir = __DIR__.'/Resource';
        $bm = new BundleManager($this->bundle_locations, vfsStream::url('root/config'), $cache_dir);

        $bm->setDebug(true);

        $this->assertFileExists($cache_dir.'/bundle_config.php');

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $config_cache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(false));

        $bm->setConfigCache($config_cache);
        $bm->loadAppBundleConfig();
    }

    public function testAppBundleConfigNoInjection()
    {
        // We get an exception because Symfony's Filesystem doesn't support vfsStream
        $this->setExpectedException('Symfony\Component\Filesystem\Exception\IOException');

        $this->bundle_manager->loadAppBundleConfig();
    }

    public function testFindBundleDirectoryException()
    {
        $this->setExpectedException('AVCMS\Core\Bundle\Exception\NotFoundException');

        $this->bundle_manager->findBundleDirectory('BadBundle');
    }

    public function testDecorateContainer()
    {
        $mock_container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $mock_container->expects($this->once())
            ->method('register');

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundle_manager->setConfigCache($config_cache);
        $this->bundle_manager->decorateContainer($mock_container);

        $this->assertTrue($this->bundle_manager->bundlesInitialized());
    }

    public function testNonExistantServiceException()
    {
        $this->setExpectedException('AVCMS\Core\Bundle\Exception\NotFoundException', "Service class");

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();

        $bm = new BundleManager(array(vfsStream::url('root/BadBundles')), vfsStream::url('root/BadBundles/config'), vfsStream::url('root/BadBundles/cache'));
        $bm->setConfigCache($config_cache);

        $mock_container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $bm->decorateContainer($mock_container);
    }

    public function testLoadBundleConfig()
    {
        $config = $this->bundle_manager->loadBundleConfig('FakeBundle');

        $this->assertEquals('FakeBundle', $config['name']);
    }

    public function testGetBundleConfig()
    {
        $config = $this->bundle_manager->getBundleConfig('FakeBundle');

        $this->assertCount(1, $this->bundle_manager->getBundleConfigs());

        $this->assertEquals('FakeBundle', $config['name']);

        $this->assertTrue($this->bundle_manager->hasBundle('FakeBundle'));

        $config_cached = $this->bundle_manager->getBundleConfig('FakeBundle');

        $this->assertEquals('FakeBundle', $config_cached['name']);
    }

    public function testGetBundleLocations()
    {
        $this->assertEquals(array(vfsStream::url('root/Bundles')), $this->bundle_manager->getBundleLocations());
    }

    public function testGetBundleSettings()
    {
        $bm = new BundleManager($this->bundle_locations, vfsStream::url('root/config'), __DIR__.'/Resource');

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $config_cache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(false));

        $bm->setConfigCache($config_cache);

        $bm->initBundles();

        $mock_settings = $this->getMockBuilder('AVCMS\Core\SettingsManager\SettingsManager')
            ->disableOriginalConstructor()
            ->getMock();
        $mock_settings->expects($this->once())->method('addSettings');

        $bm->getBundleSettings($mock_settings);
    }

    public function testGetBundleRoutes()
    {
        $bm = new BundleManager($this->bundle_locations, vfsStream::url('root/config'), __DIR__.'/Resource');

        $config_cache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $config_cache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(true));
        $config_cache->expects($this->never())->method('write');

        $bm->setConfigCache($config_cache);

        $bm->initBundles();

        $mock_collection = $this->getMock('Symfony\Component\Routing\RouteCollection');
        $mock_collection->expects($this->once())->method('addCollection');

        $bm->getBundleRoutes($mock_collection);
    }
}
