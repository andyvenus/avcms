<?php
/**
 * User: Andy
 * Date: 08/09/2014
 * Time: 13:49
 */

namespace AVCMS\Core\Module\Twig;

use AVCMS\Core\Module\ModuleManager;

class ModuleManagerTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;

        $this->templates = $moduleManager->getTemplateTypes();
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'modules' => new \Twig_SimpleFunction(
                'modules',
                array($this, 'getModules'),
                array('is_safe' => array('html'))
            ),
            'render_modules' => new \Twig_SimpleFunction(
                'render_modules',
                array($this, 'renderModules'),
                array('is_safe' => array('html'))
            )
        );
    }

    public function getModules($position, $vars = array())
    {
        $modules = $this->moduleManager->getPositionModules($position, $vars, true);

        foreach ($modules as $module) {
            if (!$module->getTemplate() || !$this->environment->getLoader()->exists($module->getTemplate())) {
                $module->setTemplate($this->getDefaultTemplate($module));
            }
        }

        return $modules;
    }

    public function renderModules($position, $vars = [])
    {
        $modules = $this->getModules($position, $vars);

        $allModules = '';
        foreach ($modules as $module) {
            $allModules .= $this->environment->render($module->getTemplate(), ['module' => $module]);
        }

        return $allModules;
    }

    public function getName()
    {
        return 'avcms_module_manager';
    }

    protected function getDefaultTemplate($module)
    {
        if (isset($this->templates[ $module->getTemplateType() ])) {
            return $this->templates[ $module->getTemplateType() ]['default_template'];
        }
        else {
            return '@CmsFoundation/blank_module.twig';
        }
    }
}
