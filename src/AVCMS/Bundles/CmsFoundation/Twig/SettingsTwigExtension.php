<?php
/**
 * User: Andy
 * Date: 09/11/14
 * Time: 19:03
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use AVCMS\Core\SettingsManager\SettingsManager;

class SettingsTwigExtension extends \Twig_Extension
{
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function getGlobals()
    {
        return array(
            'settings' => $this->settingsManager
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_settings';
    }
}