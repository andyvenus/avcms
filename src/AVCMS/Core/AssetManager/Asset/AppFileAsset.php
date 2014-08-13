<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 12:18
 */

namespace AVCMS\Core\AssetManager\Asset;

use Assetic\Asset\FileAsset;

class AppFileAsset extends FileAsset implements AppAssetInterface
{

    protected $template;

    protected $type;

    protected $file;

    protected $source;

    public function __construct($type, $file, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array())
    {
        $this->type = $type;
        $this->file = $file;

        $this->source = 'web/'.$type.'/'.$file;

        parent::__construct($this->source, $filters, $sourceRoot, $sourcePath, $vars);
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDevUrl($prepend = null)
    {
        return $this->source;
    }
}