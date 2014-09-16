<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 10:29
 */

namespace AVCMS\Core\Bundle\AssetLoader;

use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetLoader\AssetLoader;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Bundle\ResourceLocator;

/**
 * Class BundleAssetLoader
 * @package AVCMS\Core\Bundle\AssetLoader
 *
 * When sent to the AssetManager::load method, loads all bundle assets
 */
class BundleAssetLoader extends AssetLoader
{
    /**
     * @param BundleManagerInterface $bundleManager
     * @param ResourceLocator $resourceLocator
     */
    public function __construct(BundleManagerInterface $bundleManager, ResourceLocator $resourceLocator)
    {
        $this->bundle_manager = $bundleManager;
        $this->resource_locator = $resourceLocator;
    }

    /**
     * @param AssetManager $asset_manager
     */
    public function loadAssets(AssetManager $asset_manager)
    {
        $configs = $this->bundle_manager->getBundleConfigs();

        foreach ($configs as $config) {
            if (isset($config->assets)) {
                foreach($config['assets'] as $assetFile => $asset) {
                    if (!isset($asset['env'])) {
                        $asset['env'] = 'shared';
                    }
                    if (!isset($asset['priority'])) {
                        $asset['priority'] = 10;
                    }
                    if (!isset($asset['type'])) {
                        $asset['type'] = $this->getFiletype($assetFile);
                    }

                    $assetClass = new BundleFileAsset($config->name, $asset['type'], $assetFile);

                    $asset_manager->add($assetClass, $asset['env'], $asset['priority']);
                }
            }
        }
    }
}