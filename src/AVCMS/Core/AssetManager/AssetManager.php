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
use AVCMS\Core\AssetManager\Asset\BundleAssetInterface;
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

    public function add(BaseAsset $asset, $environment = self::SHARED, $priority = 10)
    {
        if (!method_exists($asset, 'getType')) {
            throw new \Exception('Assets passed to the add() method must implement the getType method');
        }

        if ($asset->getType() == 'javascript') {
            $this->addJavaScript($asset, $environment, $priority);
        }
        elseif ($asset->getType() == 'css') {
            $this->addCSS($asset, $environment, $priority);
        }
        else {
            throw new \Exception('The asset manager does not support assets of type '.$asset->getType());
        }
    }

    /**
     * @param BaseAsset $asset
     * @param string $environment
     * @param int $priority
     */
    public function addJavaScript(BaseAsset $asset, $environment = self::SHARED, $priority = 10)
    {
        $this->javascript[$environment][] = array('asset' => $asset, 'priority' => $priority);
    }

    /**
     * @param BaseAsset $asset
     * @param string $environment
     * @param int $priority
     */
    public function addCSS(BaseAsset $asset, $environment = self::SHARED, $priority = 10)
    {
        $this->css[$environment][] =  array('asset' => $asset, 'priority' => $priority);
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
                if ($asset instanceof BundleAssetInterface && $asset['asset']->getBundle() == $bundle && $asset['asset']->getFile() == $file) {
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

    public function getDevAssetUrls($asset_type, $environment)
    {
        $ordered_assets = $this->getOrderedAssets($asset_type, $environment);
        $asset_urls = array();
        foreach ($ordered_assets as $asset) {
            $asset_urls[] = $asset['asset']->getDevUrl('front.php/');
        }

        return $asset_urls;
    }

    public function createAsseticCollections(AsseticAssetManager $assetic, $type, $file_extension)
    {
        foreach ($this->$type as $environment => $assets) {
            if ($environment != self::SHARED) {
                $ordered_assets = $this->getOrderedAssets($type, $environment, true);

                $asset_collection = new AssetCollection($ordered_assets, array(new JSqueezeFilter()));
                $asset_collection->setTargetPath($environment.'.'.$file_extension);

                $assetic->set($environment.'_'.$type, $asset_collection);
            }
        }

        return $assetic;
    }

    protected function getOrderedAssets($type, $environment, $strip_priority = false)
    {
        if ($type != 'css' && $type != 'javascript') {
            throw new \Exception('Asset type '.$type.' is not valid');
        }

        $assets = array();
        $selected_assets = $this->$type;

        if (isset($selected_assets[$environment])) {
            $assets = array_merge($assets, $selected_assets[$environment]);
        }
        if (isset($selected_assets[AssetManager::SHARED])) {
            $assets = array_merge($assets, $selected_assets[AssetManager::SHARED]);
        }

        usort($assets, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        if ($strip_priority) {
            $stripped = array();
            foreach ($assets as $asset) {
                $stripped[] = $asset['asset'];
            }
            $assets = $stripped;
        }

        return $assets;
    }
}