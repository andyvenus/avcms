<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 12:18
 */

namespace AVCMS\Core\AssetManager\Asset;

use Assetic\Asset\FileAsset;

class BundleFileAsset extends FileAsset implements BundleAssetInterface
{

    protected $bundle;

    protected $type;

    protected $file;

    protected $source;

    /**
     * @param string $bundle
     * @param array $type
     * @param string $file
     * @param array $filters
     * @param null $sourceRoot
     * @param null $sourcePath
     * @param array $vars
     */
    public function __construct($bundle, $type, $file, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array())
    {
        $this->bundle = $bundle;
        $this->type = $type;
        $this->file = $file;

        $this->source = 'src/AVCMS/Bundles/'.$bundle.'/resources/'.$type.'/'.$file;

        parent::__construct($this->source, $filters, $sourceRoot, $sourcePath, $vars);
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

    public function getDevUrl($prepend)
    {
        return $prepend.'bundle_asset/'.$this->getBundle().'/'.$this->getType().'/'.$this->getFile();
    }
}