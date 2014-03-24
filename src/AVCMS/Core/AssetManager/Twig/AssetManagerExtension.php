<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 10:42
 */

namespace AVCMS\Core\AssetManager\Twig;

use AVCMS\Core\AssetManager\Asset\BundleAsset;
use AVCMS\Core\AssetManager\AssetManager;

class AssetManagerExtension extends \Twig_Extension
{
    protected $asset_manager;

    protected $debug;

    public function __construct(AssetManager $asset_manager, $debug = false)
    {
        $this->asset_manager = $asset_manager;
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

    protected function getProductionAssetUrls($asset_type, $environment)
    {
        if ($asset_type == 'javascript') {
            $ext = 'js';
        }
        else {
            $ext = 'css';
        }

        return array("web/compiled/$environment.$ext", "web/compiled/shared.$ext");
    }

    protected function getDevAssetUrls($asset_type, $environment)
    {
        $asset_method = 'get'.$asset_type;

        $assets = $this->asset_manager->$asset_method();

        $asset_urls = array();

        if (isset($assets[$environment])) {
            foreach ($assets[$environment] as $asset) {
                $asset_urls[] = 'front.php/'.$this->generateDevAssetUrl($asset);
            }
        }
        if (isset($assets[AssetManager::SHARED])) {
            foreach ($assets[AssetManager::SHARED] as $asset) {
                $asset_urls[] = 'front.php/'.$this->generateDevAssetUrl($asset);
            }
        }

        return $asset_urls;
    }

    protected function generateDevAssetUrl(BundleAsset $asset)
    {
        return 'asset/'.$asset->getBundle().'/'.$asset->getType().'/'.$asset->getFile();
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