<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 13:41
 */

namespace AVCMS\Core\AssetManager;

use Assetic\Asset\BaseAsset;
use Assetic\Factory\AssetFactory;
use AVCMS\Core\BundleManager\BundleManager;

class AssetManager
{
    const SHARED = 'shared';
    const FRONTEND = 'frontend';
    const ADMIN = 'admin';

    protected $javascript;

    protected $css;

    public function __construct(AssetFactory $asset_factory, BundleManager $bundle_manager, $debug = false)
    {
        if ($bundle_manager->bundlesInitialized()) {
            $bundles = $bundle_manager->getBundles();
            foreach ($bundles as $bundle) {
                $bundle['instance']->assets($this);
            }
        }
    }

    public function addJavaScript(BaseAsset $asset, $environment = self::SHARED)
    {
        $this->javascript[$environment][] = $asset;
    }

    public function addCSS(BaseAsset $asset, $environment = self::SHARED)
    {
        $this->css[$environment][] = $asset;
    }

    public function getCSS()
    {
        return $this->css;
    }

    public function getJavaScript()
    {
        return $this->javascript;
    }
}