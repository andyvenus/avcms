<?php
/**
 * User: Andy
 * Date: 19/08/2014
 * Time: 17:14
 */

namespace AV\Kernel\Bundle\Tests;

use AV\Kernel\Bundle\BundleManager;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Yaml;

class BundleManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AV\Kernel\Bundle\BundleManager
     */
    private $bundleManager;

    private $root;

    private $bundleLocations;

    public function setUp()
    {
        $this->root = vfsStream::setup('root', 0777);
        $this->root = vfsStream::create(array(
            'Bundles' => array(
                'FakeBundle' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array(
                                'name' => 'FakeBundle',
                                'namespace' => 'AV\Kernel\Bundle\Tests\Resource',
                                'services' => array(
                                    'BundleServices'
                                ),
                                'user_settings' => array(
                                    'my_setting' => array(
                                        'name' => 'my_name'
                                    )
                                ),
                                'container_params' => array(
                                    'container_param' => 'a_value'
                                )
                            )
                        ),
                        'routes.yml' => Yaml::dump(array('my_route' => array('path' => '/path'))),
                        'admin_routes.yml' => Yaml::dump(array('my_admin_route' => array('path' => '/admin-path')))
                    )
                ),
                'BundleWithParent' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array(
                                'name' => 'BundleWithParent',
                                'namespace' => 'AV\Kernel\Bundle\Tests\Resource\BundleWithParent',
                                'parent_bundle' => 'FakeBundle'
                            )
                        ),
                        'routes.yml' => Yaml::dump(array('my_route' => array('path' => '/path'))),
                    )
                ),
                'FakeDevBundle' => array(
                    'config' => array(
                        'bundle.yml' => Yaml::dump(array('name' => 'FakeDevBundle', 'namespace' => 'AV\Kernel\Bundle\Tests\Resource'))
                    )
                )
            ),
            'cache' => array(),
            'config' => array(
                'bundles.yml' => Yaml::dump(array(
                    'FakeBundle' => array(
                        'enabled' => true, 'config' => array('model' => 'Example\Model')
                    ),
                    'BundleWithParent' => array(
                        'enabled' => true
                    )
                )),
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

        $this->bundleLocations = array(vfsStream::url('root/Bundles'));
        $this->bundleManager = new BundleManager($this->bundleLocations, vfsStream::url('root/config'), vfsStream::url('root/cache'));
    }

    public function tearDown()
    {
        file_put_contents(__DIR__.'/Resource/bundle_config.php', "<?php
            return array(
                'FakeBundle' => array(
                    'enabled' => true,
                    'directory' => 'vfs://root/Bundles/FakeBundle'
                ),
                'BundleWithParent' => array(
                    'enabled' => true,
                    'directory' => 'vfs://root/Bundles/BundleWithParent'
                )
            );"
        );
    }

    public function testInjectConfiguaration()
    {
        $config = $this->getMock('Symfony\Component\Config\Definition\ConfigurationInterface');
        $bundleManager = new BundleManager(array(vfsStream::url('root/Bundles')), $config);
    }

    public function testSetDebug()
    {
        $this->assertFalse($this->bundleManager->isDebug());

        $this->bundleManager->setDebug(true);

        $this->assertTrue($this->bundleManager->isDebug());
    }

    public function testLoadAppBundleConfigNotFresh()
    {
        $bm = $this->bundleManager;

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $configCache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(false));
        $configCache->expects($this->once())->method('write')
            ->will($this->returnCallback(function($contents) {
                \PHPUnit_Framework_Assert::assertStringStartsWith('<?php', $contents);
                $config_array = eval(str_replace('<?php', '', $contents));
                \PHPUnit_Framework_Assert::assertArrayHasKey('FakeBundle', $config_array);
            }));

        $bm->setConfigCache($configCache);
        $bm->loadAppBundleConfig();
    }

    public function testLoadAppBundleConfigFresh()
    {
        $bm = new BundleManager($this->bundleLocations, vfsStream::url('root/config'), __DIR__.'/Resource');

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $configCache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(true));
        $configCache->expects($this->never())->method('write');

        $bm->setConfigCache($configCache);
        $config = $bm->loadAppBundleConfig();

        $this->assertArrayHasKey('FakeBundle', $config);
        $this->assertTrue($bm->cacheIsFresh());
    }

    public function testLoadAppBundleConfigNotFoundException()
    {
        $bm = new BundleManager(array(), 'not_exists');

        $this->setExpectedException('AV\Kernel\Bundle\Exception\NotFoundException');

        $bm->loadAppBundleConfig();
    }

    public function testLoadAppBundleConfigDev()
    {
        $cache_dir = __DIR__.'/Resource';
        $bm = new BundleManager($this->bundleLocations, vfsStream::url('root/config'), $cache_dir);

        $bm->setDebug(true);

        $this->assertFileExists($cache_dir.'/bundle_config.php');

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $configCache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(false));

        $bm->setConfigCache($configCache);
        $bm->loadAppBundleConfig();
    }

    public function testAppBundleConfigNoInjection()
    {
        // We get an exception because Symfony's Filesystem doesn't support vfsStream
        $this->setExpectedException('Symfony\Component\Filesystem\Exception\IOException');

        $this->bundleManager->loadAppBundleConfig();
    }

    public function testFindBundleDirectoryException()
    {
        $this->setExpectedException('AV\Kernel\Bundle\Exception\NotFoundException');

        $this->bundleManager->findBundleDirectory('BadBundle');
    }

    public function testDecorateContainer()
    {
        $mock_container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
        ->setMethods(['register', 'setParameter', 'addObjectResource'])
        ->getMock();
        $mock_container->expects($this->exactly(2))
            ->method('register');
        $mock_container->expects($this->exactly(2))
            ->method('setParameter');
        $mock_container->expects($this->exactly(2))
            ->method('addObjectResource');

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundleManager->setConfigCache($configCache);
        $this->bundleManager->decorateContainer($mock_container);

        $this->assertTrue($this->bundleManager->bundlesInitialized());
    }

    public function testNonExistentServiceException()
    {
        $this->setExpectedException('AV\Kernel\Bundle\Exception\NotFoundException', "Service class");

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();

        $bm = new BundleManager(array(vfsStream::url('root/BadBundles')), vfsStream::url('root/BadBundles/config'), vfsStream::url('root/BadBundles/cache'));
        $bm->setConfigCache($configCache);

        $mock_container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(null)
            ->getMock();

        $bm->decorateContainer($mock_container);
    }

    public function testLoadBundleConfig()
    {
        $config = $this->bundleManager->loadBundleConfig('FakeBundle');

        $this->assertEquals('FakeBundle', $config['name']);
    }

    public function testGetBundleConfigPreloaded()
    {
        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundleManager->setConfigCache($configCache);

        $this->bundleManager->initBundles();

        $config = $this->bundleManager->getBundleConfig('FakeBundle');

        $this->assertCount(2, $this->bundleManager->getBundleConfigs());

        $this->assertEquals('FakeBundle', $config['name']);

        $this->assertTrue($this->bundleManager->hasBundle('FakeBundle'));

        $configCached = $this->bundleManager->getBundleConfig('FakeBundle');

        $this->assertEquals('FakeBundle', $configCached['name']);
    }

    public function testGetBundleConfigOnDemand()
    {
        $config = $this->bundleManager->getBundleConfig('FakeBundle');

        $this->assertEquals('FakeBundle', $config['name']);
    }

    public function testGetBundleLocations()
    {
        $this->assertEquals(array(vfsStream::url('root/Bundles')), $this->bundleManager->getBundleLocations());
    }

    public function testGetBundleRoutes()
    {
        $bm = new BundleManager($this->bundleLocations, vfsStream::url('root/config'), __DIR__.'/Resource');

        $configCache = $this->getMockBuilder('Symfony\Component\Config\ConfigCache')
            ->disableOriginalConstructor()
            ->getMock();
        $configCache->expects($this->once())
            ->method('isFresh')->will($this->returnValue(true));
        $configCache->expects($this->never())->method('write');

        $bm->setConfigCache($configCache);

        $bm->initBundles();

        $mock_collection = $this->getMock('Symfony\Component\Routing\RouteCollection');
        $mock_collection->expects($this->exactly(3))->method('addCollection');

        $bm->getBundleRoutes($mock_collection);
    }
}
