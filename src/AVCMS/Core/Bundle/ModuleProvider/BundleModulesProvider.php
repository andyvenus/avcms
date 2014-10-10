<?php
/**
 * User: Andy
 * Date: 10/09/2014
 * Time: 20:38
 */

namespace AVCMS\Core\Bundle\ModuleProvider;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Module\Module;
use AVCMS\Core\Module\ModuleProviderInterface;

class BundleModulesProvider implements ModuleProviderInterface
{
    protected $modules;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function getModules()
    {
        if (isset($this->modules)) {
            return $this->modules;
        }

        $this->modules = array();

        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (isset($bundleConfig['modules'])) {
                foreach ($bundleConfig['modules'] as $moduleId => $module) {
                    $this->modules[$moduleId] = $moduleObj = new Module($module);
                    $moduleObj->setId($moduleId);
                }
            }
        }

        return $this->modules;

    }

    public function hasModule($moduleName)
    {
        $this->getModules();

        return isset($this->modules[$moduleName]);
    }

    public function getModule($moduleName)
    {
        $this->getModules();

        if (isset($this->modules[$moduleName])) {
            return $this->modules[$moduleName];
        }
        else {
            throw new \Exception(sprintf('Module %s not found. Check that the module exists using has() before trying to retrieve a module.', $moduleName));
        }
    }

    public function getProviderId()
    {
        return 'bundle';
    }
}