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
     * @var string
     */
    protected $rootDir;

    /**
     * @param $rootDir
     * @param $templatesDir string
     * @param bool $checkConfig
     */
    public function __construct($rootDir, $templatesDir, $checkConfig = true)
    {
        $this->rootDir = $rootDir;
        $this->templatesDir = $templatesDir;
        $this->checkConfig = $checkConfig;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $fullPath = $this->rootDir.'/'.$this->templatesDir;
        $dirs = scandir($fullPath);
        $choices = array();
        foreach ($dirs as $dir) {
            if (is_dir($fullPath.'/'.$dir) && $dir != '.' && $dir != '..' && (file_exists($fullPath.'/'.$dir.'/template.yml') || $this->checkConfig === false)) {
                $choices[$this->templatesDir.'/'.$dir] = $dir;
            }
        }

        return $choices;
    }
}
