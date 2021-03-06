<?php
/**
 * User: Andy
 * Date: 06/09/2014
 * Time: 18:31
 */

namespace AVCMS\Core\Module;

use AVCMS\Core\Module\Exception\ModuleNotFoundException;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ModuleManager
{
    protected $loadedModuleConfigs;

    /**
     * @var ModuleProviderInterface[]
     */
    protected $providers = array();

    protected $fragmentHandler;

    protected $moduleModel;

    protected $requestStack;

    protected $cacheDir;

    protected $devMode;

    protected $authChecker;

    protected $translator;

    public function __construct(FragmentHandler $fragmentHandler, ModuleConfigModelInterface $moduleModel, RequestStack $requestStack, AuthorizationCheckerInterface $authChecker, $cacheDir, $devMode = false, TranslatorInterface $translator = null)
    {
        $this->fragmentHandler = $fragmentHandler;
        $this->moduleModel = $moduleModel;
        $this->requestStack = $requestStack;
        $this->cacheDir = $cacheDir;
        $this->devMode = $devMode;
        $this->authChecker = $authChecker;
        $this->translator = $translator;
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

        $cacheFile = $this->cacheDir.'/'.$moduleConfig->getModule().'/'.$moduleConfig->getId().'-'.$position;

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

        if ($module->isCachable() && $this->devMode === false) {
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

        foreach ($module->getAdminSettings() as $settingName => $userSetting) {
            $userSettingDefaults[$settingName] = (isset($userSetting['default']) ? $userSetting['default'] : null);
        }

        $adminSettings = array_replace($userSettingDefaults, $moduleConfig->getSettingsArray());
        $moduleConfig->setSettingsArray($adminSettings);

        $vars = array_merge($vars, array('module' => $moduleConfig, 'adminSettings' => $adminSettings));

        $controller = new ControllerReference($module->getController(), $vars);

        try {
            $content = $this->fragmentHandler->render($controller, 'inline');
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        }

        if ($module->isCachable() && $moduleConfig->getCacheTime()) {
            if (!file_exists($this->cacheDir.'/'.$moduleConfig->getModule())) {
                mkdir($this->cacheDir.'/'.$moduleConfig->getModule(), 0777, true);
            }

            file_put_contents($cacheFile, $content);
        }

        return $content;
    }

    /**
     * @param $positionId
     * @param array $vars
     * @param bool $limitByRequest
     * @param bool $getContent
     * @param bool $ignorePermissions
     * @throws \Exception
     * @return \AVCMS\Bundles\CmsFoundation\Model\Module[]
     */
    public function getPositionModules($positionId, $vars = array(), $limitByRequest = false, $getContent = true, $ignorePermissions = false)
    {
        $request = $this->requestStack->getCurrentRequest();

        // No module on login page
        if ($request->attributes->get('_route') == 'login') {
            return [];
        }

        $configs = $this->loadModuleConfigs($positionId, $ignorePermissions);

        foreach ($configs as $configId => $moduleConfig) {

            if ($ignorePermissions === false && $perms = $moduleConfig->getPermissionsArray()) {
                if (!$this->authChecker->isGranted($perms)) {
                    unset($configs[$configId]);
                    continue;
                }
            }

            $routes = $moduleConfig->getLimitRoutesArray();

            if ($limitByRequest === true && is_array($routes) && !empty($routes)) {
                if (!in_array($request->attributes->get('_route'), $routes)) {
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
                    } catch (SkipModuleException $e) {
                        unset($configs[$configId]);
                        continue;
                    }

                    if ($content !== null) {
                        $moduleConfig->setContent($content);
                    }
                    else {
                        unset($configs[$configId]);
                    }
                }
            }

            if ($this->translator !== null) {
                $moduleConfig->setTitle($this->translator->trans($moduleConfig->getTitle()));
            }
        }

        return $configs;
    }

    public function getPositionModuleCount($position, $includeUnpublished = true)
    {
        return count($this->loadModuleConfigs($position, $includeUnpublished));
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
     * @param bool $includeUnpublished
     * @return ModuleConfigInterface[]
     */
    protected function loadModuleConfigs($position, $includeUnpublished = true)
    {
        if (!isset($this->loadedModuleConfigs[$position])) {
            $this->loadedModuleConfigs[$position] = $this->moduleModel->getPositionModuleConfigs($position, $includeUnpublished);
        }

        return $this->loadedModuleConfigs[$position];
    }

    public function clearCaches($modules = null)
    {
        if ($modules !== null) {
            $modules = (array)$modules;
        }
        $this->doClearCaches($this->cacheDir, $modules);
    }

    protected function doClearCaches($dir, array $dirs = null)
    {
        $files = new \DirectoryIterator($dir);
        foreach($files as $file) {
            if ($file->isFile()) {
                unlink($file->getPathName());
            }
            if ($file->isDir() && $file->isDot() === false && ($dirs === null || in_array($file->getFilename(), $dirs))) {
                $this->doClearCaches($file->getPathname());
            }
        }
    }

    public function getTemplateTypes()
    {
        return array(
            'panel' => array('default_template' => '@CmsFoundation/panel_module.twig', 'name' => 'Panel'),
            'list_panel' => array('default_template' => '@CmsFoundation/list_panel_module.twig', 'name' => 'List Panel'),
            'content' => array('default_template' => '@CmsFoundation/content_module.twig', 'name' => 'Content'),
            'none' => array('default_template' => '@CmsFoundation/blank_module.twig', 'name' => 'None'),
        );
    }
} 
