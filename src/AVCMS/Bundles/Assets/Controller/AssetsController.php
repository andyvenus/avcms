<?php
/**
 * User: Andy
 * Date: 12/03/2014
 * Time: 14:59
 */

namespace AVCMS\Bundles\Assets\Controller;

use Assetic\AssetWriter;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends AdminBaseController
{
    public function generateAssetsAction(Request $request)
    {
        $fileMover = $this->get('public_file_mover');
        $fileMover->doMove(true);

        $assetManager = $this->get('asset_manager');
        $error = null;

        try {
            $assetManager->generateProductionAssets(new AssetWriter('web/compiled'));
        }
        catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($request->get('ajax_depth') !== null || !$request->isXmlHttpRequest()) {
            return new Response($this->renderAdminSection(
                    '@Assets/asset_regen.twig',
                    $request->get('ajax_depth'),
                    array('error' => $error)
                )
            );
        }
        else {
            return new JsonResponse(['success' => is_null($error), 'error' => $error]);
        }
    }
}