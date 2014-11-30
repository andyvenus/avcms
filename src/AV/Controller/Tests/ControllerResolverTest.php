<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 21:30
 */

namespace AV\Controller\Tests {

    use AV\Controller\ControllerResolver;
    use AV\Kernel\Bundle\BundleConfig;
    use Symfony\Component\HttpFoundation\Request;
    use Test\Controller\TestClass;

    class ControllerResolverTest extends \PHPUnit_Framework_TestCase
    {
        private $mockContainer;

        /**
         * @var \PHPUnit_Framework_MockObject_MockObject
         */
        private $mockBundleManager;

        /**
         * @var ControllerResolver
         */
        private $controllerResolver;

        public function setUp()
        {
            $this->mockContainer = $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
                ->getMock();

            $this->mockBundleManager = $this->getMockBuilder('AV\Kernel\Bundle\BundleManager')
                ->disableOriginalConstructor()
                ->getMock();

            $this->controllerResolver = new ControllerResolver($this->mockContainer, $this->mockBundleManager);
        }

        public function testCreateClassMethodController()
        {
            $request = new Request();
            $request->attributes->set('_controller', 'Test\Controller\TestClass::testAction');

            $controller = $this->controllerResolver->getController($request);

            $this->assertInstanceOf('Test\Controller\TestClass', $controller[0]);
            $this->assertEquals('testAction', $controller[1]);
        }

        public function testCreateBundleClassMethodController()
        {
            $this->mockBundleManager->expects($this->once())
                ->method('hasBundle')
                ->willReturn(true);

            $bundleConfig = new BundleConfig(['namespace' => 'Test']);

            $this->mockBundleManager->expects($this->once())
                ->method('getBundleConfig')
                ->willReturn($bundleConfig);

            $request = new Request();
            $request->attributes->set('_controller', 'TestBundle::TestClass::testAction');

            $controller = $this->controllerResolver->getController($request);

            $this->assertInstanceOf('Test\Controller\TestClass', $controller[0]);
            $this->assertEquals('testAction', $controller[1]);

            $this->assertEquals($bundleConfig, $controller[0]->bundle);
            $this->assertEquals($this->mockContainer, $controller[0]->container);
        }

        public function testCreateBundleClassMethodControllerInvalidException()
        {
            $this->setExpectedException('\Exception', 'Cannot build controller TestBundle::TestClass::testAction - Bundle TestBundle not initialised');

            $request = new Request();
            $request->attributes->set('_controller', 'TestBundle::TestClass::testAction');

            $this->controllerResolver->getController($request);
        }

        public function testInvalidController()
        {
            $this->setExpectedException('\Exception', 'Unable to find controller');

            $request = new Request();
            $request->attributes->set('_controller', 'not-a-controller');

            $this->controllerResolver->getController($request);
        }

        public function testMissingController()
        {
            $this->setExpectedException('\Exception', 'Class "MissingClass" does not exist.');

            $request = new Request();
            $request->attributes->set('_controller', 'MissingClass::method');

            $this->controllerResolver->getController($request);
        }

        public function testGetArguments()
        {
            $controller = new TestClass();

            $request = new Request();

            $this->controllerResolver->getArguments($request, $controller);
            $this->controllerResolver->getArguments($request, [$controller, 'testAction']);

            $this->assertEquals(2, $controller->setUpCount);
        }
    }
}

namespace Test\Controller {
    use Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use Symfony\Component\DependencyInjection\ContainerInterface;

    class TestClass implements ContainerAwareInterface
    {
        public $bundle;

        public $container;

        public $setUpCount = 0;

        public function setUp()
        {
            $this->setUpCount++;
        }

        public function testAction()
        {
            return 'foo';
        }

        public function setContainer(ContainerInterface $container = null)
        {
            $this->container = $container;
        }

        public function setBundle($bundle)
        {
            $this->bundle = $bundle;
        }

        public function __invoke()
        {

        }
    }
}