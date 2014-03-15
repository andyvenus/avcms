<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 12:18
 */

namespace AVCMS\Core\AssetManager\Asset;

use Assetic\Asset\FileAsset;

class BundleFileAsset extends FileAsset implements BundleAsset
{

    protected $bundle;

    protected $type;

    protected $file;

    public function __construct($bundle, $type, $file, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array())
    {
        $this->bundle = $bundle;
        $this->type = $type;
        $this->file = $file;

        $source = 'src/AVCMS/Bundles/'.$bundle.'/resources/'.$type.'/'.$file;

        parent::__construct($source, $filters, $sourceRoot, $sourcePath, $vars);
    }

    public function getBundle()
    {
        return $this->bundle;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFile()
    {
        return $this->file;
    }
}