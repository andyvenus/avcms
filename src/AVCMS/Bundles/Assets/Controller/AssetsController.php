<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 14:59
 */

namespace AVCMS\Bundles\Assets\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
    public function getAssetAction($bundle, $asset, $type)
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
            foreach ($asset_environment as $asset_obj) {
                if ($asset_obj->getBundle() == $bundle && $asset_obj->getType() == $type && $asset_obj->getFile() == $asset) {
                    $selected_asset = $asset_obj;
                    break;
                }
            }
        }

        if (!isset($selected_asset)) {
            return new Response('Not found', 404);
        }

        return new Response($selected_asset->dump(), 200, array('Content-Type' => 'text/javascript'));
    }
}