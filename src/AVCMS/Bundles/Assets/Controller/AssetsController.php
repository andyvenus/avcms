<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 14:59
 */

namespace AVCMS\Bundles\Assets\Controller;

use Assetic\AssetWriter;
use AVCMS\Core\AssetManager\Asset\BundleAssetInterface;
use AVCMS\Core\AssetManager\Asset\TemplateAssetInterface;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
    public function generateAssetsAction()
    {
        $fileMover = $this->get('public_file_mover');
        $fileMover->doMove(true);

        $assetManager = $this->get('asset_manager');
        $assetManager->generateProductionAssets(new AssetWriter('web/compiled'));

        return new Response('Assets generated');
    }
}