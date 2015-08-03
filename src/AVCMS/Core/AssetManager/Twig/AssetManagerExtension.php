<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 10:42
 */

namespace AVCMS\Core\AssetManager\Twig;

use AVCMS\Core\AssetManager\AssetManager;

class AssetManagerExtension extends \Twig_Extension
{
    protected $assetManager;

    protected $debug;

    protected $lastGenFile;

    protected $lastGen;

    public function __construct($debug = false, AssetManager $assetManager, $lastGenFile)
    {
        $this->assetManager = $assetManager;
        $this->debug = $debug;
        $this->lastGenFile = $lastGenFile;
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

        $urls = ["web/compiled/$environment.$ext?x={$this->getLastGen()}"];
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

    private function getLastGen()
    {
        if (!isset($this->lastGen)) {
            if (!is_readable($this->lastGenFile)) {
                $this->lastGen = 0;
            }

            $this->lastGen = (int) file_get_contents($this->lastGenFile);
        }

        return $this->lastGen;
    }
}
