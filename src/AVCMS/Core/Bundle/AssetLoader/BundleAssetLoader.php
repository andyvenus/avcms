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
     * @param BundleManagerInterface $bundle_manager
     * @param ResourceLocator $resource_locator
     */
    public function __construct(BundleManagerInterface $bundle_manager, ResourceLocator $resource_locator)
    {
        $this->bundle_manager = $bundle_manager;
        $this->resource_locator = $resource_locator;
    }

    /**
     * @param AssetManager $asset_manager
     */
    public function loadAssets(AssetManager $asset_manager)
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

                    $asset_class = new BundleFileAsset($config->name, $asset['type'], $asset_file);

                    $asset_manager->add($asset_class, $asset['env'], $asset['priority']);
                }
            }
        }
    }
}