<?php
/**
 * User: Andy
 * Date: 03/02/15
 * Time: 13:06
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LicenseController extends AdminBaseController
{
    public function licenseKeyAction()
    {
        // We need the master request because this controller is called in a sub-request
        $request = $this->container->get('request.stack')->getMasterRequest();

        $formBp = new FormBlueprint();
        $formBp->add('license_key', 'text', ['label' => 'Your License Key', 'help' => 'Find it at http://www.avscripts.net']);

        $form = $this->buildForm($formBp, $request);

        if ($form->isSubmitted()) {
            if ($this->container->get('subscriber.license')->checkKey($form->getData('license_key'))) {
                file_put_contents($this->getParam('root_dir').'/webmaster/license.php', '<?php return "'.$form->getData('license_key').'";');
                return new JsonResponse(['form' => $form->createView()->getJsonResponseData(), 'redirect' => $this->generateUrl('admin_dashboard', ['license' => 'done'])]);
            }
            else {
                $form->addCustomErrors([new FormError('license_key', 'Invalid License Key')]);
                return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
            }
        }

        return new Response($this->renderAdminSection('@AVScripts/admin/license_key_page.twig', ['form' => $form->createView()]));
    }
}
