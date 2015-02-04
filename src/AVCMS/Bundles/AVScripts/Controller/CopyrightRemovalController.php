<?php
/**
 * User: Andy
 * Date: 04/02/15
 * Time: 11:19
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\AVScripts\Form\CopyrightRemovalForm;
use Curl\Curl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CopyrightRemovalController extends AdminBaseController
{
    public function copyrightRemovalAction(Request $request)
    {
        $appInfo = $this->container->getParameter('app_config')['info'];
        $webmasterDir = $this->container->getParameter('root_dir').'/webmaster';

        $form = $this->buildForm(new CopyrightRemovalForm($this->setting('copyright_message')), $request);

        if ($form->isSubmitted()) {
            $this->model('CmsFoundation:Settings')->saveSetting('copyright_message', $form->getData('copyright_message'));

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        $apiResponse = null;
        if (file_exists($webmasterDir.'/license.php')) {
            $curl = new Curl();

            $apiResponse = $curl->post($this->container->getParameter('avs_api_url') . '/validate-copyright-removal', ['app_id' => $appInfo['id'], 'license_key' => include $webmasterDir . '/license.php']);

            if (!is_object($apiResponse)) {
                $apiResponse = ['success' => false, 'error' => 'Invalid Server Response'];
            }
        }
        else {
            $apiResponse = ['success' => true];
        }

        return new Response($this->renderAdminSection('@AVScripts/admin/copyright_removal.twig', $request->get('ajax_depth'), ['api_response' => $apiResponse, 'form' => $form->createView()]));
    }
}
