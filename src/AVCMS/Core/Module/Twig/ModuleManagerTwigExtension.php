<?php
/**
 * User: Andy
 * Date: 08/09/2014
 * Time: 13:49
 */

namespace AVCMS\Core\Module\Twig;

use AVCMS\Core\Module\ModuleManager;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(ModuleManager $moduleManager, UrlGeneratorInterface $urlGenerator, AuthorizationCheckerInterface $authChecker, SettingsManager $settingsManager)
    {
        $this->moduleManager = $moduleManager;
        $this->urlGenerator = $urlGenerator;
        $this->authChecker = $authChecker;
        $this->settingsManager = $settingsManager;

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
            ),
            'has_modules' => new \Twig_SimpleFunction(
                'has_modules',
                array($this, 'hasModules'),
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

        $allModules = '<div class="avcms-module-position" style="position: relative;">';

        if ($this->authChecker->isGranted('ADMIN_MODULES') && $this->settingsManager->getSetting('show_module_buttons')) {
            $cssPosition = '';
            if (count($modules) > 0) {
                $cssPosition = 'position: absolute;';
            }

            $url = $this->urlGenerator->generate('modules_manage_position', ['id' => $position]);
            $allModules .= '<div class="avcms-module-position-button" style="text-align:right;opacity:0.5;top:0;right:0;' . $cssPosition . '">
                <a class="btn btn-xs btn-default" href="' . $url . '"><span class="glyphicon glyphicon-pencil"></span></a>
            </div>';
        }

        foreach ($modules as $module) {
            $allModules .= $this->environment->render($module->getTemplate(), ['module' => $module]);
        }

        return $allModules . '</div>';
    }

    public function hasModules($modulePosition)
    {
        return $this->moduleManager->getPositionModuleCount($modulePosition, false);
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
