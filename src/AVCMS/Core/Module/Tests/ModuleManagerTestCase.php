<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 13:32
 */

namespace AVCMS\Core\Module\Tests;

use AVCMS\Core\Module\Module;
use AVCMS\Core\Module\ModuleManager;
use Mockery;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\HttpFoundation\Request;

class ModuleManagerTestCase extends \PHPUnit_Framework_TestCase
{
    protected $mockModuleConfigModel;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockFragmentHandler;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    protected $mockRequestStack;

    /**
     * @var \Mockery\MockInterface
     */
    protected $mockProvider;

    /**
     * @var \Mockery\MockInterface|\AVCMS\Core\Module\ModuleConfigInterface
     */
    protected $mockModuleConfigOne;

    protected $testModule;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $rootDir;

    public function setUp()
    {
        $this->mockFragmentHandler = $this->getMockBuilder('Symfony\Component\HttpKernel\Fragment\FragmentHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockModuleConfigModel = $this->getMockBuilder('AVCMS\Core\Module\ModuleConfigModelInterface')
            ->disableOriginalConstructor()
            ->getMock();


        $this->testModule = new Module(array(
            'id' => 'test_module',
            'controller' => 'TestController',
            'cachable' => 1,
            'user_settings' => array(
                'user_setting' => array(
                    'default' => 'default_user_setting'
                )
            )
        ));

        $this->mockModuleConfigOne = Mockery::mock('AVCMS\Core\Module\ModuleConfigInterface', array(
            'getId' => 1,
            'getLimitRoutesArray' => array('example_route'),
            'getModule' => 'test_module',
            'getPosition' => 'test_position',
            'getModuleInfo' => $this->testModule,
            'getSettingsArray' => array(),
            'getCacheTime' => 100
        ))->shouldIgnoreMissing();

        $mockConfigs = array(
            1 => $this->mockModuleConfigOne
        );

        $this->mockModuleConfigModel->expects($this->any())
            ->method('getPositionModuleConfigs')
            ->willReturn($mockConfigs);

        $positions = Mockery::mock('AV\Model\Model');

        $this->mockRequestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack', array('getCurrentRequest' => new Request()));

        $this->rootDir = vfsStream::setup('root', 0777);
        vfsStream::create(array('cache' => array()));

        $mockAuthChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationChecker')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moduleManager = new ModuleManager($this->mockFragmentHandler, $this->mockModuleConfigModel, $this->mockRequestStack, $mockAuthChecker, vfsStream::url('root/cache'));

        $this->mockProvider = Mockery::mock('AVCMS\Core\Module\ModuleProviderInterface', array(
            'getModules' => array('test_module' => $this->testModule)
        ))->shouldIgnoreMissing();

        $this->moduleManager->setProvider($this->mockProvider);
    }

    protected function tearDown()
    {
        \Mockery::close();
    }
}
 