<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 12:18
 */

namespace AVCMS\Core\AssetManager\Asset;

use Assetic\Asset\FileAsset;

class TemplateFileAsset extends FileAsset implements TemplateAssetInterface
{

    protected $template;

    protected $type;

    protected $file;

    protected $source;

    public function __construct($environment, $template, $type, $file, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array())
    {
        $this->template = $template;
        $this->type = $type;
        $this->file = $file;
        $this->environment = $environment;

        $this->source = 'templates/'.$environment.'/'.$template.'/'.$type.'/'.$file;

        parent::__construct($this->source, $filters, $sourceRoot, $sourcePath, $vars);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getDevUrl($prepend)
    {
        return $prepend.'template_asset/'.$this->getEnvironment().'/'.$this->getTemplate().'/'.$this->getType().'/'.$this->getFile();
    }
}