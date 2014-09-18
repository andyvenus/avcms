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

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;

        $this->templates = array(
            'plain' => array('template' => '@CmsFoundation/plain_module.twig'),
            'contained' => array('template' => '@CmsFoundation/contained_module.twig'),
            'none' => array('template' => '@CmsFoundation/blank_module.twig'),
        );
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
            )
        );
    }

    public function getModules($position)
    {
        $modules = $this->moduleManager->getPositionModules($position, true);

        foreach ($modules as $module) {
            if (isset($this->templates[ $module->getTemplateStyle() ])) {
                $template = $this->templates[ $module->getTemplateStyle() ]['template'];
            }
            else {
                $template = '@CmsFoundation/plain_module.twig';
            }

            $module->setTemplate($template);
        }

        return $modules;
    }



    public function getName()
    {
        return 'avcms_module_manager';
    }
}