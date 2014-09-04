<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 11:35
 */

namespace AVCMS\Core\View\AssetLoader;

use AVCMS\Core\AssetManager\Asset\TemplateFileAsset;
use AVCMS\Core\AssetManager\AssetLoader\AssetLoader;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\View\TemplateManager;

class TemplateAssetLoader extends AssetLoader
{
    protected $template_manager;

    protected $template_config;

    public function __construct(TemplateManager $template_manager)
    {
        $this->template_manager = $template_manager;
    }

    public function loadAssets(AssetManager $asset_manager)
    {
        $this->template_config = $this->template_manager->getTemplateConfig();

        if (isset($this->template_config['assets']) && is_array($this->template_config['assets']) && !empty($this->template_config['assets'])) {
            foreach ($this->template_config['assets'] as $file => $asset_config) {
                $asset_type = $this->getFiletype($file);

                $asset_config['priority'] = (isset($asset_config['priority']) ? $asset_config['priority'] : 10);

                $asset = new TemplateFileAsset($this->template_manager->getCurrentTemplate(), $asset_type, $file);

                $asset_manager->add($asset, 'frontend', $asset_config['priority']);
            }
        }
    }
}