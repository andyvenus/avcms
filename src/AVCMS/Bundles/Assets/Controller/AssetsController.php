<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 14:59
 */

namespace AVCMS\Bundles\Assets\Controller;

use AVCMS\Core\AssetManager\Asset\BundleAssetInterface;
use AVCMS\Core\AssetManager\Asset\TemplateAssetInterface;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
    public function getAssetAction($asset_file, $type, $bundle = null, $template = null, $environment = null)
    {
        /**
         * @var $bundle_manager \AVCMS\Core\BundleManager\BundleManager
         */
        $asset_manager = $this->get('asset_manager');

        $manager_getter = "get".$type;

        if (!method_exists($asset_manager, $manager_getter)) {
            throw new \Exception('Not a valid asset type');
        }

        $assets = $asset_manager->$manager_getter();

        foreach ($assets as $asset_environment) {
            foreach ($asset_environment as $asset) {
                if ($bundle != null && $asset['asset'] instanceof BundleAssetInterface) {
                    if ($asset['asset']->getBundle() == $bundle && $asset['asset']->getType() == $type && $asset['asset']->getFile() == $asset_file) {
                        $selected_asset = $asset['asset'];
                        break;
                    }
                }
                else if ($template != null && $asset['asset'] instanceof TemplateAssetInterface) {
                    if ($asset['asset']->getEnvironment() == $environment && $asset['asset']->getTemplate() == $template && $asset['asset']->getType() == $type && $asset['asset']->getFile() == $asset_file) {
                        $selected_asset = $asset['asset'];
                        break;
                    }
                }
            }
        }

        if (!isset($selected_asset)) {
            return new Response('Not found', 404);
        }

        return new Response($selected_asset->dump(), 200, array('Content-Type' => 'text/'.$type));
    }

    public function generateAssetsAction()
    {
        $asset_manager = $this->get('asset_manager');
        $asset_manager->generateProductionAssets();

        return new Response('Assets generated');
    }
}