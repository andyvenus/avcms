<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 20:43
 */

namespace AV\Kernel\Bundle\Tests;

use AV\Kernel\Bundle\BundleConfig;
use AV\Kernel\Bundle\ResourceLocator;
use org\bovigo\vfs\vfsStream;

class ResourceLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockBundleManager;

    /**
     * @var ResourceLocator
     */
    private $resourceLocator;

    private $rootDir;

    private $bundleConfig;

    public function setUp()
    {
        $this->rootDir = vfsStream::setup('root', 0777);
        $this->rootDir = vfsStream::create(array(
            'rootDir' => array(
                'test-bundle' => array(
                    'resources' => array(
                        'text' => array(
                            'foo.txt' => 'foo',
                            'bar.txt' => 'bar'
                        )
                    )
                ),
                'test-parent' => array(
                    'resources' => array(
                        'text' => array(
                            'baz.txt' => 'baz'
                        )
                    )
                )
            ),
            'appDir' => array(
                'resources' => array(
                    'test-bundle' => array(
                        'bar.txt' => 'bar2'
                    )
                )
            )
        ));

        $this->mockBundleManager = $this->getMockBuilder('AV\Kernel\Bundle\BundleManager')
            ->disableOriginalConstructor()
            ->setMethods(['getBundleConfig', 'hasBundle'])
            ->getMock();

        $this->bundleConfig = new BundleConfig(array(
            'name' => 'test-bundle',
            'directory' => 'test-bundle',
            'parent_config' => array(
                'name' => 'test-parent',
                'directory' => 'test-parent',
            )
        ));

        $this->resourceLocator = new ResourceLocator($this->mockBundleManager, vfsStream::url('root/rootDir'), vfsStream::url('root/appDir'));
    }

    public function testFindFileDirectory()
    {
        $this->mockBundleManager->expects($this->exactly(3))
            ->method('getBundleConfig')
            ->willReturn($this->bundleConfig)
        ;

        $location = $this->resourceLocator->findFileDirectory('test-name', 'foo.txt', 'text');

        $this->assertEquals('vfs://root/rootDir/test-bundle/resources/text/foo.txt', $location);

        $parentLocation = $this->resourceLocator->findFileDirectory('test-name', 'baz.txt', 'text');

        $this->assertEquals('vfs://root/rootDir/test-parent/resources/text/baz.txt', $parentLocation);

        $appLocation = $this->resourceLocator->findFileDirectory('test-name', 'bar.txt', 'text');

        $this->assertEquals('vfs://root/appDir/resources/test-bundle/bar.txt', $appLocation);
    }

    public function testFindNonExistentFile()
    {
        $this->setExpectedException('AV\Kernel\Bundle\Exception\NotFoundException');

        $this->mockBundleManager->expects($this->once())
            ->method('getBundleConfig')
            ->willReturn($this->bundleConfig)
        ;

        $this->resourceLocator->findFileDirectory('test-name', 'does-not-exist.txt', 'text');
    }

    public function testBundleExists()
    {
        $this->mockBundleManager->expects($this->once())
            ->method('hasBundle')
        ;

        $this->resourceLocator->bundleExists('test-bundle');
    }
}
 