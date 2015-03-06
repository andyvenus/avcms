<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 11:35
 */

namespace AVCMS\Core\View\AssetLoader;

use Assetic\Filter\JSqueezeFilter;
use AVCMS\Core\AssetManager\Asset\TemplateFileAsset;
use AVCMS\Core\AssetManager\AssetLoader\AssetLoader;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\View\TemplateManager;

class TemplateAssetLoader extends AssetLoader
{
    protected $templateManager;

    protected $templateConfig;

    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    public function loadAssets(AssetManager $assetManager)
    {
        $this->templateConfig = $this->templateManager->getTemplateConfig();

        if (isset($this->templateConfig['assets']) && is_array($this->templateConfig['assets']) && !empty($this->templateConfig['assets'])) {
            foreach ($this->templateConfig['assets'] as $file => $assetConfig) {
                $assetType = $this->getFiletype($file);

                $assetConfig['priority'] = (isset($assetConfig['priority']) ? $assetConfig['priority'] : 10);

                $templateConfig = $this->templateManager->getTemplateConfig();

                $template = $this->templateManager->getCurrentTemplate();

                if (!file_exists($this->templateManager->getCurrentTemplate().'/'.$assetType.'/'.$file) && isset($templateConfig['parent_template_dir'])) {
                    $template = $templateConfig['parent_template_dir'];
                }

                $filters = [];
                if ((!isset($assetConfig['compress']) || $assetConfig['compress'] !== false) && $assetType == 'javascript') {
                    $filters = [new JSqueezeFilter()];
                }

                $asset = new TemplateFileAsset($template, $assetType, $file, $filters);

                $assetManager->add($asset, 'frontend', $assetConfig['priority']);
            }
        }
    }
}
