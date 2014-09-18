<?php
/**
 * User: Andy
 * Date: 06/09/2014
 * Time: 18:31
 */

namespace AVCMS\Core\Module;

use AVCMS\Core\Model\Model;
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

    public function __construct(FragmentHandler $fragmentHandler, Model $moduleModel, Model $modulePositionsModel, RequestStack $requestStack, $cacheDir)
    {
        $this->fragmentHandler = $fragmentHandler;
        $this->moduleModel = $moduleModel;
        $this->modulePositionsModel = $modulePositionsModel;
        $this->requestStack = $requestStack;
        $this->cacheDir = $cacheDir;
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

    public function getModuleContent($moduleConfig, $section)
    {
        $module = $moduleConfig->getModuleInfo();

        if (!$module) return null;

        if (isset($module['cachable']) && $module['cachable']) {
            $cacheFile = $this->cacheDir.'/'.$moduleConfig->getModule().'-'.$section.'.php';

            if (!file_exists($cacheFile)) {
                $cacheFresh = false;
            }
            elseif (filemtime($cacheFile) < (time() - $moduleConfig->getCacheTime())) {
                $cacheFresh = false;
            }
            else {
                $cacheFresh = true;
            }

            if ($cacheFresh) {
                return file_get_contents($cacheFile);
            }
        }

        $controller = new ControllerReference($module['controller'], array('module' => $moduleConfig));

        return $this->fragmentHandler->render($controller, 'inline');
    }

    /**
     * @param $position
     * @param bool $limitByRequest
     * @return \AVCMS\Bundles\CmsFoundation\Model\Module[]
     */
    public function getPositionModules($position, $limitByRequest = false)
    {
        $configs = $this->loadModuleConfigs($position);

        foreach ($configs as $i => $moduleConfig) {
            $routes = $moduleConfig->getLimitRoutesArray();
            
            if ($limitByRequest == true && is_array($routes) && !empty($routes)) {
                if (!in_array($this->requestStack->getCurrentRequest()->attributes->get('_route'), $routes)) {
                    unset($configs[$i]);
                }
            }

            if (isset($configs[$i])) {
                try {
                    $moduleConfig->setModuleInfo($this->getModule($moduleConfig->getModule()));
                    $content = $this->getModuleContent($moduleConfig, $position);
                } catch (ModuleNotFoundException $e) {
                    $content = $e->getMessage();
                }

                $moduleConfig->setContent($content);
            }
        }

        return $configs;
    }

    /**
     * @param $moduleId
     * @throws ModuleNotFoundException
     * @return array
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

    protected function loadModuleConfigs($position)
    {
        if (!isset($this->loadedModuleConfigs[$position])) {
            $this->loadedModuleConfigs[$position] = $this->moduleModel->find()->where('position', $position)->customOrder('order')->get();
        }

        return $this->loadedModuleConfigs[$position];
    }
} 