<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:00
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdaterAdminController extends AdminBaseController
{
    public function updatesAction(Request $request)
    {
        $appConfigInfo = $this->getParam('app_config')['info'];

        $key = null;
        if (file_exists($this->getParam('root_dir').'/webmaster/license.php')) {
            $key = include $this->getParam('root_dir').'/webmaster/license.php';
        }

        $downloadUrl = $this->getParam('avs_api_url').'/download-latest?app_id='.$appConfigInfo['id'].'&license_key='.$key;

        return new Response($this->renderAdminSection('@AVScripts/admin/check_for_update.twig', ['download_url' => $downloadUrl]));
    }

    public function checkForUpdateAction()
    {
        $updateManager = $this->container->get('update_checker');

        $upToDate = $updateManager->isUpToDate();

        if ($updateManager->isUpToDate() === true || $upToDate  === null) {
            $jsonResponse = json_encode(['update_available' => ($upToDate === null ? null : !$upToDate), 'status_message' => $updateManager->getStatusMessage()]);
        }
        else {
            $jsonResponse = json_encode((array)$updateManager->getUpdateInfo() + ['update_available' => true]);
        }

        file_put_contents($this->getParam('cache_dir').'/update_info.json', $jsonResponse);

        return new Response($jsonResponse, 200, ['Content-Type' => 'application/json']);
    }
}
