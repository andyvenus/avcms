<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 10:42
 */

namespace AVCMS\Core\AssetManager\Twig;

use Assetic\Asset\BaseAsset;
use AVCMS\Core\AssetManager\Asset\BundleAsset;
use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetManager;

class AssetManagerExtension extends \Twig_Extension
{
    protected $assetManager;

    protected $debug;

    public function __construct($debug = false, AssetManager $assetManager)
    {
        if ($debug == true && $assetManager == null) {
            throw new \Exception("The asset manager must be set in debug mode");
        }

        $this->assetManager = $assetManager;
        $this->debug = $debug;
    }

    public function getFunctions()
    {
        return array(
            'javascripts' => new \Twig_SimpleFunction(
                'javascripts',
                array($this, 'javascripts'),
                array('is_safe' => array('html'))
            ),
            'css' => new \Twig_SimpleFunction(
                'css',
                array($this, 'css'),
                array('is_safe' => array('html'))
            )
        );
    }

    public function javascripts($environment)
    {
        if ($this->debug) {
            return $this->getDevAssetUrls('javascript', $environment);
        }
        else {
            return $this->getProductionAssetUrls('javascript', $environment);
        }
    }

    public function css($environment)
    {
        if ($this->debug) {
            return $this->getDevAssetUrls('css', $environment);
        }
        else {
            return $this->getProductionAssetUrls('css', $environment);
        }
    }

    protected function getProductionAssetUrls($assetType, $environment)
    {
        if ($assetType == 'javascript') {
            $ext = 'js';
        }
        else {
            $ext = 'css';
        }

        $urls = ["web/compiled/$environment.$ext"];
        $urls = array_merge($urls, $this->assetManager->getRawAssetUrls($assetType, $environment));

        return $urls;
    }

    protected function getDevAssetUrls($assetType, $environment)
    {
        return $this->assetManager->getDevAssetUrls($assetType, $environment);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_asset_manager';
    }
}