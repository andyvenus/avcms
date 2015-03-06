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

    protected $filename;

    protected $source;

    /**
     * @param string $bundle
     * @param string $type
     * @param null|string $filename
     * @param array $filters
     * @param null $sourceRoot
     * @param null $sourcePath
     * @param array $vars
     */
    public function __construct($bundle, $type, $filename, $filters = array(), $sourceRoot = null, $sourcePath = null, array $vars = array())
    {
        $this->bundle = $bundle;
        $this->type = $type;
        $this->filename = $filename;

        $this->source = 'web/resources/'.$bundle.'/'.$type.'/'.$filename;

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

    public function getFilename()
    {
        return $this->filename;
    }

    public function getDevUrl($prepend = null)
    {
        return 'web/resources/'.$this->getBundle().'/'.$this->getType().'/'.$this->getFilename();
    }
}
