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
        $modules = $this->moduleManager->getPositionModules($position);

        $positionContent = '';
        foreach ($modules as $module) {
            $positionContent .= $this->environment->render('@CmsFoundation/contained_module.twig', array('module' => $module));
        }

        return $positionContent;
    }

    public function getName()
    {
        return 'avcms_module_manager';
    }
}