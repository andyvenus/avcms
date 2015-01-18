<?php
/**
 * User: Andy
 * Date: 16/08/2014
 * Time: 10:54
 */

namespace AVCMS\Bundles\CmsFoundation\Settings;

use AV\Form\ChoicesProviderInterface;

class TemplateChoicesProvider implements ChoicesProviderInterface
{
    /**
     * @var string
     */
    protected $templatesDir;

    /**
     * @var bool
     */
    protected $checkConfig;

    /**
     * @param $templatesDir string
     * @param bool $checkConfig
     */
    public function __construct($templatesDir, $checkConfig = true)
    {
        $this->templatesDir = $templatesDir;
        $this->checkConfig = $checkConfig;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $dirs = scandir($this->templatesDir);
        $choices = array();
        foreach ($dirs as $dir) {
            if (is_dir($this->templatesDir.'/'.$dir) && $dir != '.' && $dir != '..' && (file_exists($this->templatesDir.'/'.$dir.'/template.yml') || $this->checkConfig === false)) {
                $choices[$this->templatesDir.'/'.$dir] = $dir;
            }
        }

        return $choices;
    }
}
