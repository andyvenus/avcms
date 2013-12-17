<?php
/**
 * User: Andy
 * Date: 17/12/2013
 * Time: 21:41
 */

class TemplateSettings {

    protected $overrides;

    public function __construct()
    {
        $this->overrideTemplate('@games/first_template.twig', '@template/first_template');

        $this->addCss('style.css');
        $this->addJavascript('js/js_file.js');
    }


    /// Base Class ///
    public function overrideTemplate($original, $replacement)
    {
        $this->overrides[] = array($original => $replacement);
    }

    public function addCss()
    {

    }

    public function addJavascript()
    {

    }
} 