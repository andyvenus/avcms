<?php
/**
 * User: Andy
 * Date: 06/09/2014
 * Time: 18:31
 */

namespace AVCMS\Core\Module;

use AV\Model\Model;
use AVCMS\Core\Module\Exception\ModuleNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

class ModuleManager
{
    protected $loadedModuleConfigs;

    /**
     * @var ModuleProviderInterface[]
     */
    protected $providers = array();

    public function __construct(FragmentHandler $fragmentHandler, ModuleConfigModelInterface $moduleModel, ModulePositionsManager $modulePositionsManager, RequestStack $requestStack, $cacheDir, $devMode = false)
    {
        $this->fragmentHandler = $fragmentHandler;
        $this->moduleModel = $moduleModel;
        $this->modulePositionsManager = $modulePositionsManager;
        $this->requestStack = $requestStack;
        $this->cacheDir = $cacheDir;
        $this->devMode = $devMode;
    }

    public function setProvider(ModuleProviderInterface $moduleProviders)
    {
        $this->providers[] = $moduleProviders;
    }

    public function getModuleConfig($moduleId, $section)
    {
        if (!isset($this->loadedModuleConfigs[$section])) {
            $this->loadModuleConfigs($section);
        }

        return $this->loadedModuleConfigs[$section][$moduleId];
    }

    public function getModuleContent(ModuleConfigInterface $moduleConfig, $position, $vars = array())
    {
        $module = $moduleConfig->getModuleInfo();

        if (!$module) return null;

        $cacheFile = $this->cacheDir.'/'.$moduleConfig->getModule().'-'.$moduleConfig->getId().'-'.$position;

        if ($cacheVars = $module->getCacheIdVars()) {
            foreach ($cacheVars as $varName) {
                if (!isset($vars[$varName]) || !is_object($vars[$varName]) || !is_callable([$vars[$varName], 'getId'])) {
                    throw new \Exception(sprintf("The module type '%s' requires a variable called $varName that is an object with a 'getId'
                    method. Likely an entity such as a User or Comment.", $module->getName()));
                }

                $cacheFile .= '-'.$varName.$vars[$varName]->getId();
            }
        }

        $cacheFile .= '.php';

        if ($module->isCachable() && $this->devMode == false) {
            if (!file_exists($cacheFile)) {
                $cacheFresh = false;
            }
            elseif (filemtime($cacheFile) <= (time() - $moduleConfig->getCacheTime())) {
                $cacheFresh = false;
            }
            else {
                $cacheFresh = true;
            }

            if ($cacheFresh) {
                return file_get_contents($cacheFile);
            }
        }

        $userSettingDefaults = array();

        foreach ($module->getUserSettings() as $settingName => $userSetting) {
            $userSettingDefaults[$settingName] = (isset($userSetting['default']) ? $userSetting['default'] : null);
        }

        $userSettings = array_replace($userSettingDefaults, $moduleConfig->getSettingsArray());
        $moduleConfig->setSettingsArray($userSettings);

        $vars = array_merge($vars, array('module' => $moduleConfig, 'userSettings' => $userSettings));

        $controller = new ControllerReference($module->getController(), $vars);

        try {
            $content = $this->fragmentHandler->render($controller, 'inline');
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        }

        if ($module->isCachable() && $moduleConfig->getCacheTime()) {
            file_put_contents($cacheFile, $content);
        }

        return $content;
    }

    /**
     * @param $positionId
     * @param $vars
     * @param bool $limitByRequest
     * @param bool $getContent
     * @return \AVCMS\Bundles\CmsFoundation\Model\Module[]
     */
    public function getPositionModules($positionId, $vars = array(), $limitByRequest = false, $getContent = true)
    {
        $configs = $this->loadModuleConfigs($positionId);

        foreach ($configs as $configId => $moduleConfig) {
            $routes = $moduleConfig->getLimitRoutesArray();
            
            if ($limitByRequest == true && is_array($routes) && !empty($routes)) {
                if (!in_array($this->requestStack->getCurrentRequest()->attributes->get('_route'), $routes)) {
                    unset($configs[$configId]);
                }
            }

            if (isset($configs[$configId])) {
                try {
                    $moduleInfo = $this->getModule($moduleConfig->getModule());
                } catch (ModuleNotFoundException $e) {
                    $moduleInfo = null;
                }

                $moduleConfig->setModuleInfo($moduleInfo);

                if ($getContent) {
                    try {
                        $content = $this->getModuleContent($moduleConfig, $positionId, $vars);
                    } catch (ModuleNotFoundException $e) {
                        $content = $e->getMessage();
                    }

                    $moduleConfig->setContent($content);
                }
            }
        }

        return $configs;
    }

    public function getPositionModuleCount($position)
    {
        return count($this->loadModuleConfigs($position));
    }

    public function getPosition($position)
    {
        foreach ($this->providers as $provider) {
            if ($provider->hasPosition($position)) {
                return $provider->getPosition($position);
            }
        }

        return null;
    }

    /**
     * @param $moduleId
     * @throws ModuleNotFoundException
     * @return Module
     */
    public function getModule($moduleId)
    {
        foreach ($this->providers as $provider) {
            if ($provider->hasModule($moduleId)) {
                return $provider->getModule($moduleId);
            }
        }

        throw new ModuleNotFoundException(sprintf('Module with id %s not found', $moduleId));
    }

    public function getAllModules()
    {
        $modules = array();
        foreach ($this->providers as $provider) {
            $modules = array_merge($modules, $provider->getModules());
        }

        return $modules;
    }

    /**
     * @param $position
     * @return ModuleConfigInterface[]
     */
    protected function loadModuleConfigs($position)
    {
        if (!isset($this->loadedModuleConfigs[$position])) {
            $this->loadedModuleConfigs[$position] = $this->moduleModel->getPositionModuleConfigs($position);
        }

        return $this->loadedModuleConfigs[$position];
    }

    public function clearCaches()
    {
        $files = new \DirectoryIterator($this->cacheDir);
        foreach($files as $file) {
            if ($file->isFile()) {
                unlink($file->getPathName());
            }
        }
    }

    public function getTemplateTypes()
    {
        return array(
            'panel' => array('default_template' => '@CmsFoundation/panel_module.twig', 'name' => 'Panel'),
            'list_panel' => array('default_template' => '@CmsFoundation/list_panel_module.twig', 'name' => 'List Panel'),
            'none' => array('default_template' => '@CmsFoundation/blank_module.twig', 'name' => 'None'),
        );
    }
} 