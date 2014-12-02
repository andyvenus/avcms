<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 15:47
 */

namespace AVCMS\Core\Module\Tests;

use org\bovigo\vfs\vfsStream;

class ModuleManagerTest extends ModuleManagerTestCase
{
    public function testGetModuleConfig()
    {
        $config = $this->moduleManager->getModuleConfig(1, 'main');

        $this->assertInstanceOf('AVCMS\Core\Module\ModuleConfigInterface', $config);
        $this->assertEquals('test_module', $config->getModule());
    }

    public function testGetModuleContent()
    {
        $this->mockFragmentHandler->expects($this->exactly(2))
            ->method('render')
            ->willReturn('module-content');

        $content = $this->moduleManager->getModuleContent($this->mockModuleConfigOne, 'test_position');

        $this->assertEquals('module-content', $content);
        $this->assertTrue(file_exists(vfsStream::url('root/cache').'/test_module/1-test_position.php'));

        $this->moduleManager->getModuleContent($this->mockModuleConfigOne, 'test_position');

        $this->moduleManager->clearCaches();

        $this->assertFalse(file_exists(vfsStream::url('root/cache').'/test_module/1-test_position.php'));

        $this->moduleManager->getModuleContent($this->mockModuleConfigOne, 'test_position');
    }

    public function testGetPositionModules()
    {
        $this->mockFragmentHandler->expects($this->once())
            ->method('render')
            ->willReturn('module-content');

        $this->mockModuleConfigOne->shouldReceive('setContent');

        $this->mockProvider->shouldReceive('hasModule')->once()->with('test_module')->andReturn(true);

        $modules = $this->moduleManager->getPositionModules('test_position');

        $this->assertCount(1, $modules);
        $this->assertEquals($this->mockModuleConfigOne, $modules[1]);
    }

    public function testGetAllModules()
    {
        $modules = $this->moduleManager->getAllModules();

        $this->assertCount(1, $modules);
    }

    public function testModuleNotFoundException()
    {
        $this->setExpectedException('AVCMS\Core\Module\Exception\ModuleNotFoundException');

        $this->moduleManager->getModule('non_existant');
    }
}