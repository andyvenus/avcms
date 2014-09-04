<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 10:35
 */

namespace AVCMS\Core\AssetManager\AssetLoader;

use AVCMS\Core\AssetManager\AssetManager;

interface AssetLoaderInterface
{
    public function loadAssets(AssetManager $asset_manager);
}