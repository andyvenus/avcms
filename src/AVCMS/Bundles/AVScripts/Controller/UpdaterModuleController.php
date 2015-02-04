<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 17:29
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UpdaterModuleController extends Controller
{
    public function updaterModule()
    {
        $appConfigInfo = $this->container->getParameter('app_config')['info'];

        $downloadUrl = $this->container->getParameter('avs_api_url').'/download-latest?app_id='.$appConfigInfo['id'];

        return new Response($this->render('@AVScripts/admin/update_info.twig', ['download_url' => $downloadUrl]));
    }
}