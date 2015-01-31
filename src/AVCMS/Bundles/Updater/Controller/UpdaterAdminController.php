<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:00
 */

namespace AVCMS\Bundles\Updater\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Updater\UpdateChecker\UpdateChecker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdaterAdminController extends AdminBaseController
{
    public function updatesAction(Request $request)
    {
        $appConfigInfo = $this->container->getParameter('app_config')['info'];

        $downloadUrl = UpdateChecker::SERVER.'/download-latest?app_id='.$appConfigInfo['id'];

        return new Response($this->renderAdminSection('@Updater/admin/check_for_update.twig', $request->get('ajax_depth'), ['app_info' => $this->container->getParameter('app_config')['info'], 'download_url' => $downloadUrl]));
    }

    public function checkForUpdateAction()
    {
        $updateManager = $this->container->get('update_checker');

        if ($updateManager->isUpToDate() === true || $updateManager->isUpToDate() === null) {
            return new JsonResponse(['update_available' => !$updateManager->isUpToDate(), 'status_message' => $updateManager->getStatusMessage()]);
        }

        return new JsonResponse((array) $updateManager->getUpdateInfo() + ['update_available' => true]);
    }
}