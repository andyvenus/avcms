<?php
/**
 * User: Andy
 * Date: 27/07/2014
 * Time: 18:11
 */

namespace AVCMS\Core\Bundle\BundleBuilder;

use AVCMS\Core\Bundle\BundleConfig;

class FileMaker
{
    protected $files = array();

    protected $vars;

    protected $file_base_path = '';

    public function __construct(BundleConfig $bundle_config, $database, $plural, $singular)
    {
        $this->bundle_config = $bundle_config;

        $this->vars['namespace'] = $this->bundle_config->namespace;
        $this->vars['singular'] = $singular;
        $this->vars['plural'] = $plural;
        $this->vars['uc_singular'] = ucfirst($singular);
        $this->vars['uc_plural'] = ucfirst($plural);

        $this->vars['admin_home_route'] = $this->vars['uc_plural'].'_admin_home';
        $this->vars['admin_home_route'] = $this->vars['uc_plural'].'_admin_finder';
        $this->vars['admin_home_route'] = $this->vars['uc_plural'].'_admin_delete';

        $this->vars['finder_item_extra_buttons'] = '';
        $this->vars['database'] = $database;

        $this->vars['entity'] = $this->vars['namespace'].'\Model\\'.$this->vars['uc_singular'];
    }

    public function addFile($file, $target_directory)
    {
        if (!file_exists($this->file_base_path.'/'.$file)) {
            throw new \Exception(sprintf("Can't find file: %s", $file));
        }

        $this->files[] = array('file' => $this->file_base_path.'/'.$file, 'target' => $target_directory);
    }

    public function setVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function processAndSaveFiles($overwrite = false)
    {
        foreach ($this->files as $file) {
            $file_target = $this->bundle_config->directory.'/'.$this->processVars($file['target']);
            if ($overwrite === false && file_exists($file_target)) {
                throw new \Exception(sprintf("File target %s already exists", $file['target']));
            }

            $contents = $this->processVars(file_get_contents($file['file']));

            file_put_contents($file_target, $contents);
        }
    }

    public function processVars($string)
    {
        foreach ($this->vars as $var_name => $var) {
            $string = str_replace("{{{$var_name}}}", $var, $string);
        }

        return $string;
    }

    public function setFileBasePath($path)
    {
        $this->file_base_path = $path;
    }
}