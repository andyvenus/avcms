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
    const NO_OVERWRITE = false;

    const OVERWRITE = true;

    const APPEND = 'append';

    protected $files = array();

    protected $vars;

    protected $file_base_path = '';

    public function __construct(BundleConfig $bundle_config, $database, $plural, $singular, $title_field)
    {
        $this->bundle_config = $bundle_config;

        $this->vars['bundle'] = $this->bundle_config->name;
        $this->vars['namespace'] = $this->bundle_config->namespace;
        $this->vars['singular'] = $singular;
        $this->vars['plural'] = $plural;
        $this->vars['uc_singular'] = ucwords(str_replace('_', ' ', $singular));
        $this->vars['uc_plural'] = ucwords(str_replace('_', ' ', $plural));
        $this->vars['cc_singular'] = $this->dashesToCamelCase($singular, true);
        $this->vars['cc_plural'] = $this->dashesToCamelCase($plural, true);

        $this->vars['admin_home_route'] = $this->vars['plural'].'_admin_home';
        $this->vars['admin_finder_route'] = $this->vars['plural'].'_admin_finder';
        $this->vars['admin_delete_route'] = $this->vars['plural'].'_admin_delete';
        $this->vars['admin_edit_route'] = $this->vars['plural'].'_admin_edit';
        $this->vars['admin_add_route'] = $this->vars['plural'].'_admin_add';

        $this->vars['admin_controller'] = $this->bundle_config->name.'::'.$this->vars['cc_plural'].'AdminController';

        $this->vars['finder_item_extra_buttons'] = '';
        $this->vars['database'] = $database;

        $this->vars['entity'] = $this->vars['namespace'].'\Model\\'.$this->vars['cc_singular'];
        $this->vars['admin_form'] = $this->vars['namespace'].'\Form\\'.$this->vars['cc_singular'].'AdminForm';

        if ($singular == $plural) {
            $this->vars['model_class'] = $this->vars['cc_plural'].'Model';
        }
        else {
            $this->vars['model_class'] = $this->vars['cc_plural'];
        }

        $this->vars['title_field'] = $title_field;
    }

    public function addFile($file, $target_directory, $overwrite = self::NO_OVERWRITE)
    {
        if (!file_exists($this->file_base_path.'/'.$file)) {
            throw new \Exception(sprintf("Can't find file: %s", $file));
        }

        $this->files[] = array('file' => $this->file_base_path.'/'.$file, 'target' => $target_directory, 'overwrite' => $overwrite);
    }

    public function setVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function processAndSaveFiles($overwrite_all = false)
    {
        foreach ($this->files as $file) {
            $file_target = $this->bundle_config->directory.'/'.$this->processVars($file['target']);
            if ($overwrite_all === false && $file['overwrite'] === false && file_exists($file_target)) {
                throw new \Exception(sprintf("File target %s already exists", $file_target));
            }

            $contents = $this->processVars(file_get_contents($file['file']));

            if ($file['overwrite'] !== 'append') {
                $flags = 0;
            }
            else {
                $flags = FILE_APPEND;
            }

            file_put_contents($file_target, $contents, $flags);
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

    public function getVars()
    {
        return $this->vars;
    }

    public function getVar($name)
    {
        return $this->vars[$name];
    }

    protected function dashesToCamelCase($string, $capitalize_first_character = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalize_first_character) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}