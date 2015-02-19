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
        $appConfigInfo = $this->getParam('app_config')['info'];

        $downloadUrl = $this->getParam('avs_api_url').'/download-latest?app_id='.$appConfigInfo['id'];

        $cacheFile = $this->getParam('cache_dir').'/update_info.json';
        $json = null;
        if (file_exists($cacheFile) && filemtime($cacheFile) > time() - 86400) {
            $json = file_get_contents($cacheFile);
        }

        return new Response($this->render('@AVScripts/admin/update_info.twig', [
            'download_url' => $downloadUrl,
            'json' => $json
        ]));
    }
}
