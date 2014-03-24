<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 13:41
 */

namespace AVCMS\Core\AssetManager;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\BaseAsset;
use Assetic\AssetWriter;
use Assetic\Filter\JSqueezeFilter;
use AVCMS\Core\BundleManager\BundleManager;
use Assetic\AssetManager as AsseticAssetManager;

class AssetManager
{
    const SHARED = 'shared';
    const FRONTEND = 'frontend';
    const ADMIN = 'admin';
    const ALL_ASSETS = 'all';
    const ALL_TYPES = 'all';

    /**
     * @var array The javascript assets
     */
    protected $javascript = array();

    /**
     * @var array The CSS assets
     */
    protected $css = array();

    /**
     * @param BundleManager $bundle_manager
     * @param bool $debug
     */
    public function __construct(BundleManager $bundle_manager, $debug = false)
    {
        if ($bundle_manager->bundlesInitialized()) {
            $bundles = $bundle_manager->getBundles();
            foreach ($bundles as $bundle) {
                $bundle['instance']->assets($this);
            }
        }
    }

    /**
     * @param BaseAsset $asset
     * @param string $environment
     */
    public function addJavaScript(BaseAsset $asset, $environment = self::SHARED)
    {
        $this->javascript[$environment][] = $asset;
    }

    /**
     * @param BaseAsset $asset
     * @param string $environment
     */
    public function addCSS(BaseAsset $asset, $environment = self::SHARED)
    {
        $this->css[$environment][] = $asset;
    }

    /**
     * @return array
     */
    public function getCSS()
    {
        return $this->css;
    }

    /**
     * @return array
     */
    public function getJavaScript()
    {
        return $this->javascript;
    }

    /**
     * Remove an asset by bundle name, asset type and filename
     * Useful for allowing templates to remove assets it wants to replace
     *
     * @param $bundle string
     * @param $type string
     * @param $file string
     * @return bool
     * @throws \Exception
     */
    public function removeBundleAsset($bundle, $type, $file)
    {
        if ($type != 'css' && $type != 'javascript') {
            throw new \Exception("Invalid asset type '$type'. Only 'css' or 'javascript' are valid");
        }

        foreach ($this->$type as $env_key => $environment) {
            foreach ($environment as $asset_key => $asset) {
                if ($asset->getBundle() == $bundle && $asset->getFile() == $file) {
                    unset($this->{$type}[$env_key][$asset_key]);

                    $bundle_removed = true;
                }
            }
        }

        return isset($bundle_removed);
    }

    public function generateProductionAssets()
    {
        $assetic = new AsseticAssetManager();

        $this->createAsseticCollections($assetic, 'javascript', 'js');
        $this->createAsseticCollections($assetic, 'css', 'css');

        $writer = new AssetWriter('web/compiled');
        $writer->writeManagerAssets($assetic);
    }

    public function createAsseticCollections(AsseticAssetManager $assetic, $type, $file_extension)
    {
        foreach ($this->$type as $environment => $assets) {
            $asset_collection = new AssetCollection($assets, array(new JSqueezeFilter()));
            $asset_collection->setTargetPath($environment.'.'.$file_extension);

            $assetic->set($environment.'_'.$type, $asset_collection);
        }

        return $assetic;
    }
}