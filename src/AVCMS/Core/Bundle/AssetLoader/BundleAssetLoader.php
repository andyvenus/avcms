<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 10:29
 */

namespace AVCMS\Core\Bundle\AssetLoader;

use Assetic\Filter\JSqueezeFilter;
use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetLoader\AssetLoader;
use AVCMS\Core\AssetManager\AssetManager;

/**
 * Class BundleAssetLoader
 * @package AVCMS\Core\Bundle\AssetLoader
 *
 * When sent to the AssetManager::load method, loads all bundle assets
 */
class BundleAssetLoader extends AssetLoader
{
    /**
     * @var BundleManagerInterface
     */
    protected $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    /**
     * @param AssetManager $assetManager
     */
    public function loadAssets(AssetManager $assetManager)
    {
        $configs = $this->bundleManager->getBundleConfigs();

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

                    $filters = [];
                    if ((!isset($asset['compress']) || $asset['compress'] !== false) && $asset['type'] == 'javascript') {
                        $filters = [new JSqueezeFilter()];
                    }

                    $assetClass = new BundleFileAsset($config->name, $asset['type'], $assetFile, $filters);

                    if (!isset($asset['compile']) || $asset['compile'] === true) {
                        $assetManager->add($assetClass, $asset['env'], $asset['priority']);
                    }
                    else {
                        $assetManager->addRawAsset($assetClass, $asset['env']);
                    }
                }
            }
        }
    }
}
