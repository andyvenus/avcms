<?php
/**
 * User: Andy
 * Date: 10/12/14
 * Time: 15:05
 */

namespace AV\Bundles\Twig\TwigExtension;

class AppConfigTwigExtension extends \Twig_Extension
{
    private $appConfig;

    public function __construct(array $appConfig)
    {
        $this->appConfig = $appConfig;
    }

    public function getGlobals()
    {
        return [
            'app_config' => $this->appConfig
        ];
    }

    public function getName()
    {
        return 'av_app_config';
    }
}