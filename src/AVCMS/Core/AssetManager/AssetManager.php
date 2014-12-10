<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 13:41
 */

namespace AVCMS\Core\AssetManager;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\BaseAsset;
use Assetic\AssetManager as AsseticAssetManager;
use Assetic\AssetWriter;
use Assetic\Filter\JSqueezeFilter;
use AVCMS\Core\AssetManager\Asset\BundleAssetInterface;
use AVCMS\Core\AssetManager\AssetLoader\AssetLoader;
use AVCMS\Core\AssetManager\Exception\AssetTypeException;
use AVCMS\Core\AssetManager\Filters\BundleUrlRewriteFilter;

class AssetManager
{
    const SHARED = 'shared';
    const FRONTEND = 'frontend';
    const ADMIN = 'admin';
    const ALL_ASSETS = 'all';
    const ALL_TYPES = 'all';

    protected $assets = [
        'javascript' => [
            self::FRONTEND => array(),
            self::ADMIN => array(),
            self::SHARED => array()
        ],
        'css' => [
            self::FRONTEND => array(),
            self::ADMIN => array(),
            self::SHARED => array()
        ]
    ];

    protected $rawAssetUrls = [
        'javascript' => [
            self::FRONTEND => array(),
            self::ADMIN => array(),
            self::SHARED => array()
        ],
        'css' => [
            self::FRONTEND => array(),
            self::ADMIN => array(),
            self::SHARED => array()
        ]
    ];

    public function add(BaseAsset $asset, $environment = self::SHARED, $priority = 10)
    {
        if (!method_exists($asset, 'getType')) {
            throw new \Exception('Assets passed to the add() method must implement the getType method');
        }

        $this->assets[$asset->getType()][$environment][] = array('asset' => $asset, 'priority' => $priority);
    }

    public function addRawAsset(BaseAsset $asset, $environment = self::SHARED)
    {
        if (!method_exists($asset, 'getType')) {
            throw new \Exception('Assets passed to the addRawAsset() method must implement the getType method');
        }

        $this->rawAssetUrls[$asset->getType()][$environment][] = $asset->getDevUrl();
    }

    public function getRawAssetUrls($type, $env)
    {
        return $this->rawAssetUrls[$type][$env];
    }

    public function load(AssetLoader $assetLoader)
    {
        $assetLoader->loadAssets($this);
    }

    /**
     * @return array
     */
    public function getCSS()
    {
        return $this->assets['css'];
    }

    /**
     * @return array
     */
    public function getJavaScript()
    {
        return $this->assets['javascript'];
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

        foreach ($this->assets[$type] as $envKey => $environment) {
            foreach ($environment as $assetKey => $asset) {
                if ($asset['asset'] instanceof BundleAssetInterface && $asset['asset']->getBundle() == $bundle && $asset['asset']->getFilename() == $file) {
                    unset($this->assets[$type][$envKey][$assetKey]);

                    $bundleRemoved = true;
                }
            }
        }

        return isset($bundleRemoved);
    }

    public function generateProductionAssets(AssetWriter $writer)
    {
        $assetic = new AsseticAssetManager();

        $this->createAsseticCollections($assetic, 'javascript', 'js', array(new JSqueezeFilter()));
        $this->createAsseticCollections($assetic, 'css', 'css', array(new BundleUrlRewriteFilter()));

        $writer->writeManagerAssets($assetic);
    }

    public function getDevAssetUrls($assetType, $environment)
    {
        $orderedAssets = $this->getOrderedAssets($assetType, $environment);
        $assetUrls = array();
        foreach ($orderedAssets as $asset) {
            if (method_exists($asset['asset'], 'getDevUrl')) {
                $assetUrls[] = $asset['asset']->getDevUrl();
            }
        }

        $assetUrls = array_merge($assetUrls, $this->rawAssetUrls[$assetType][$environment]);

        return $assetUrls;
    }

    public function createAsseticCollections(AsseticAssetManager $assetic, $type, $fileExtension, $filters = array())
    {
        foreach ($this->assets[$type] as $environment => $assets) {
            if ($environment != self::SHARED) {
                $orderedAssets = $this->getOrderedAssets($type, $environment, true);

                $assetCollection = new AssetCollection($orderedAssets, $filters);
                $assetCollection->setTargetPath($environment.'.'.$fileExtension);

                $assetic->set($environment.'_'.$type, $assetCollection);
            }
        }

        return $assetic;
    }

    protected function getOrderedAssets($type, $environment, $stripPriority = false)
    {
        if ($type != 'css' && $type != 'javascript') {
            throw new AssetTypeException('Asset type '.$type.' is not valid');
        }

        $assets = array();
        $selectedAssets = $this->assets[$type];

        if (isset($selectedAssets[$environment])) {
            $assets = array_merge($assets, $selectedAssets[$environment]);
        }
        if (isset($selectedAssets[AssetManager::SHARED])) {
            $assets = array_merge($assets, $selectedAssets[AssetManager::SHARED]);
        }

        usort($assets, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        if ($stripPriority) {
            $stripped = array();
            foreach ($assets as $asset) {
                $stripped[] = $asset['asset'];
            }
            $assets = $stripped;
        }

        return $assets;
    }
}