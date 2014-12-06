<?php
/**
 * User: Andy
 * Date: 06/12/14
 * Time: 11:54
 */

namespace AV\Kernel\Tests;

use AV\Kernel\Tests\Fixtures\BundleKernelForTest;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class BundleKernelTest extends \PHPUnit_Framework_TestCase
{
    private $filesystem;

    private $root;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHttpKernel;

    public function setUp()
    {
        $this->filesystem = vfsStream::setup('root', 0777);

        $this->root = vfsStream::create(array(
            'app' => [
                'config' => [
                    'app.yml' => Yaml::dump(['bundle_dirs' => [vfsStream::url('root/bundles_dir')]])
                ]
            ],
            'cache' => [],
            'web' => [],
            'bundles_dir' => []
        ));
    }

    /**
     * @param $expectedException
     * @param $exceptionMessage
     * @param $rootDirs
     *
     * @dataProvider constructExceptionsProvider
     */
    public function testConstructExceptions($expectedException, $exceptionMessage, $rootDirs)
    {
        $this->setExpectedException($expectedException, $exceptionMessage);

        $this->root = vfsStream::create($rootDirs);

        $kernel = new BundleKernelForTest(vfsStream::url('root'), true, $this->getKernelOptions());

        $bundleManager = $this->getBundleManager();
        $kernel->setBundleManager($bundleManager);

        $kernel->boot();
    }

    public function constructExceptionsProvider()
    {
        $this->filesystem = vfsStream::setup('root', 0777);

        return [
            ['AV\Kernel\Exception\KernelConfigException', 'No app config (app.yml) found', array('app' => ['config' => []])],
            ['AV\Kernel\Exception\KernelConfigException', 'App config (app.yml) does not contain any bundle directories', array('app' => ['config' => ['app.yml' => '']])]
        ];
    }

    public function testDebugBoot()
    {
        $options = $this->getKernelOptions();

        $kernel = new BundleKernelForTest(vfsStream::url('root'), true, $options);

        $bundleManager = $this->getBundleManager();
        $bundleManager->expects($this->once())
            ->method('decorateContainer')
            ->willReturnCallback([$this, 'addServices'])
        ;
        $bundleManager->expects($this->once())
            ->method('initBundles');

        $kernel->setBundleManager($bundleManager);

        $kernel->boot();
    }

    public function testHandle()
    {
        $options = $this->getKernelOptions();

        $kernel = new BundleKernelForTest(vfsStream::url('root'), true, $options);

        $bundleManager = $this->getBundleManager();
        $bundleManager->expects($this->once())
            ->method('decorateContainer')
            ->willReturnCallback([$this, 'addServices'])
        ;

        $kernel->setBundleManager($bundleManager);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $kernel->handle(Request::createFromGlobals()));
    }

    public function getKernelOptions()
    {
        $options['app_dir'] = vfsStream::url('root/app');
        $options['cache_dir'] = vfsStream::url('root/cache');
        $options['web_path'] = vfsStream::url('root/web');
        $options['container_cache'] = false;

        return $options;
    }

    public function getBundleManager()
    {
        $mock = $this->getMockBuilder('AV\Kernel\Bundle\BundleManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $mock;
    }


    public function addServices()
    {
        $args = func_get_args();
        $container = $args[0];

        $container->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');
        $container->register('bundle_manager', 'AV\Kernel\Bundle\BundleManagerAlias');
        $container->register('http_kernel', 'Symfony\Component\HttpKernel\Tests\TestHttpKernel');
        $container->register('context', 'Symfony\Component\Routing\RequestContext');
    }
}
 