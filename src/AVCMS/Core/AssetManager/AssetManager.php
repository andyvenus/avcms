<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 13:41
 */

namespace AVCMS\Core\AssetManager;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\BaseAsset;
use Assetic\Asset\FileAsset;
use Assetic\AssetWriter;
use Assetic\Filter\JSqueezeFilter;
use AVCMS\Core\AssetManager\Asset\AppFileAsset;
use AVCMS\Core\AssetManager\Asset\BundleAssetInterface;
use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\Exception\AssetTypeException;
use AVCMS\Core\Bundle\BundleManager;
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
        $this->bundle_manager = $bundle_manager;

        $this->loadBundleAssets();
    }

    public function add(BaseAsset $asset, $environment = self::SHARED, $priority = 10)
    {
        if (!method_exists($asset, 'getType')) {
            throw new \Exception('Assets passed to the add() method must implement the getType method');
        }

        $this->{$asset->getType()}[$environment][] = array('asset' => $asset, 'priority' => $priority);
    }

    public function addBundleAsset($bundle_name, $asset, $type, $environment = self::SHARED, $priority = 10)
    {
        $src = $this->bundle_manager->getBundleResource($bundle_name, $asset, $type);

        if ($src) {
            $this->{$type}[$environment][] = array(
                'asset' => new BundleFileAsset($bundle_name, $type, $asset, $src),
                'priority' => $priority
            );
        }
    }

    public function addAppAsset($asset, $type, $environment = self::SHARED, $priority = 10)
    {
        $this->{$type}[$environment][] = array(
            'asset' => new AppFileAsset($type, $asset),
            'priority' => $priority
        );
    }

    public function loadBundleAssets()
    {
        $configs = $this->bundle_manager->getBundleConfigs();

        foreach ($configs as $config) {
            if (isset($config->assets)) {
                foreach($config['assets'] as $asset_file => $asset) {
                    if (!isset($asset['env'])) {
                        $asset['env'] = 'shared';
                    }
                    if (!isset($asset['priority'])) {
                        $asset['priority'] = 10;
                    }
                    if (!isset($asset['type'])) {
                        $asset['type'] = $this->getFiletype($asset_file);
                    }
                    $this->addBundleAsset($config->name, $asset_file, $asset['type'], $asset['env'], $asset['priority']);
                }
            }
        }
    }

    protected function getFiletype($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext == 'js') {
            return 'javascript';
        }
        else if (in_array($ext, array('css', 'scss', 'sass', 'less'))) {
            return 'css';
        }
        else {
            return 'unknown';
        }
    }

    public function getAssets($type)
    {
        return $this->{$type};
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
                if ($asset['asset'] instanceof BundleAssetInterface && $asset['asset']->getBundle() == $bundle && $asset['asset']->getFile() == $file) {
                    unset($this->{$type}[$env_key][$asset_key]);

                    $bundle_removed = true;
                }
            }
        }

        return isset($bundle_removed);
    }

    public function generateProductionAssets(AssetWriter $writer)
    {
        $assetic = new AsseticAssetManager();

        $this->createAsseticCollections($assetic, 'javascript', 'js');
        $this->createAsseticCollections($assetic, 'css', 'css');

        $writer->writeManagerAssets($assetic);
    }

    public function getDevAssetUrls($asset_type, $environment)
    {
        $ordered_assets = $this->getOrderedAssets($asset_type, $environment);
        $asset_urls = array();
        foreach ($ordered_assets as $asset) {
            if (method_exists($asset['asset'], 'getDevUrl')) {
                $asset_urls[] = $asset['asset']->getDevUrl();
            }
        }

        return $asset_urls;
    }

    public function createAsseticCollections(AsseticAssetManager $assetic, $type, $file_extension)
    {
        foreach ($this->$type as $environment => $assets) {
            if ($environment != self::SHARED) {
                $ordered_assets = $this->getOrderedAssets($type, $environment, true);

                $asset_collection = new AssetCollection($ordered_assets, array(new JSqueezeFilter())); //todo: CSS minifiy rather than always JS
                $asset_collection->setTargetPath($environment.'.'.$file_extension);

                $assetic->set($environment.'_'.$type, $asset_collection);
            }
        }

        return $assetic;
    }

    protected function getOrderedAssets($type, $environment, $strip_priority = false)
    {
        if ($type != 'css' && $type != 'javascript') {
            throw new AssetTypeException('Asset type '.$type.' is not valid');
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