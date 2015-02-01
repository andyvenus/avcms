<?php
/**
 * User: Andy
 * Date: 27/07/2014
 * Time: 18:11
 */

namespace AVCMS\Core\Bundle\BundleBuilder;

use AV\Kernel\Bundle\BundleConfig;

class FileMaker
{
    const NO_OVERWRITE = false;

    const OVERWRITE = true;

    const APPEND = 'append';

    protected $files = array();

    protected $vars;

    protected $fileBasePath = '';

    protected $bundleConfig;

    public function __construct(BundleConfig $bundleConfig, $database, $plural, $singular, $titleField)
    {
        $this->bundleConfig = $bundleConfig;

        $this->vars['bundle'] = $this->bundleConfig->name;
        $this->vars['namespace'] = $this->bundleConfig->namespace;
        $this->vars['singular'] = $singular;
        $this->vars['plural'] = $plural;
        $this->vars['uc_singular'] = ucwords(str_replace('_', ' ', $singular));
        $this->vars['uc_plural'] = ucwords(str_replace('_', ' ', $plural));
        $this->vars['cc_singular'] = $this->dashesToCamelCase($singular, true);
        $this->vars['cc_plural'] = $this->dashesToCamelCase($plural, true);

        $this->vars['dash_singular'] = str_replace('_', '-', $singular);
        $this->vars['dash_plural'] = str_replace('_', '-', $plural);

        $this->vars['admin_home_route'] = $this->vars['plural'].'_admin_home';
        $this->vars['admin_finder_route'] = $this->vars['plural'].'_admin_finder';
        $this->vars['admin_delete_route'] = $this->vars['plural'].'_admin_delete';
        $this->vars['admin_edit_route'] = $this->vars['plural'].'_admin_edit';
        $this->vars['admin_add_route'] = $this->vars['plural'].'_admin_add';

        $this->vars['admin_controller'] = $this->bundleConfig->name.'::'.$this->vars['cc_plural'].'AdminController';

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

        $this->vars['model_var_name'] = lcfirst($this->vars['cc_plural']);

        $this->vars['title_field'] = $titleField;
    }

    public function addFile($file, $targetDirectory, $overwrite = self::NO_OVERWRITE)
    {
        if (!file_exists($this->fileBasePath.'/'.$file)) {
            throw new \Exception(sprintf("Can't find file: %s", $file));
        }

        $this->files[] = array('file' => $this->fileBasePath.'/'.$file, 'target' => $targetDirectory, 'overwrite' => $overwrite);
    }

    public function setVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    public function processAndSaveFiles($overwriteAll = false)
    {
        foreach ($this->files as $file) {
            $fileTarget = $this->bundleConfig->directory.'/'.$this->processVars($file['target']);
            if ($overwriteAll === false && $file['overwrite'] === false && file_exists($fileTarget)) {
                throw new \Exception(sprintf("File target %s already exists", $fileTarget));
            }

            $contents = $this->processVars(file_get_contents($file['file']));

            if ($file['overwrite'] !== 'append') {
                $flags = 0;
            }
            else {
                $flags = FILE_APPEND;
            }

            file_put_contents($fileTarget, $contents, $flags);
        }
    }

    public function processVars($string)
    {
        foreach ($this->vars as $varName => $var) {
            $string = str_replace("{{".$varName."}}", $var, $string);
        }

        return $string;
    }

    public function setFileBasePath($path)
    {
        $this->fileBasePath = $path;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function getVar($name)
    {
        return $this->vars[$name];
    }

    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}
