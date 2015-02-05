<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:00
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdaterAdminController extends AdminBaseController
{
    public function updatesAction(Request $request)
    {
        $appConfigInfo = $this->getParam('app_config')['info'];

        $downloadUrl = $this->getParam('avs_api_url').'/download-latest?app_id='.$appConfigInfo['id'];

        return new Response($this->renderAdminSection('@AVScripts/admin/check_for_update.twig', ['download_url' => $downloadUrl]));
    }

    public function checkForUpdateAction()
    {
        $updateManager = $this->container->get('update_checker');

        $upToDate = $updateManager->isUpToDate();

        if ($updateManager->isUpToDate() === true || $upToDate  === null) {
            return new JsonResponse(['update_available' => ($upToDate === null ? null : !$upToDate), 'status_message' => $updateManager->getStatusMessage()]);
        }

        return new JsonResponse((array) $updateManager->getUpdateInfo() + ['update_available' => true]);
    }
}
