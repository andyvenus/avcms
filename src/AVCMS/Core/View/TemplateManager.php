<?php
/**
 * User: Andy
 * Date: 18/05/2014
 * Time: 17:50
 */

namespace AVCMS\Core\View;

class TemplateManager
{
    public function __construct($container, $template, $environment)
    {
        $this->template = $template;

        $template_settings = TemplateSettings($this);
        $environment = $template_settings->environment();
    }

    public function addCSS($css)
    {
        //
    }

    public function addJavascript($javascript)
    {
        //
    }

    public function requireBundle($bundle_name)
    {

    }
}