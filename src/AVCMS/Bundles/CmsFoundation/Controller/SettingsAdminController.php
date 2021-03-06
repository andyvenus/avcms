<?php
/**
 * User: Andy
 * Date: 13/08/2014
 * Time: 10:41
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SettingsAdminController extends AdminBaseController
{
    public function setUp()
    {
        if (!$this->isGranted('ADMIN_SETTINGS')) {
            throw new AccessDeniedException;
        }
    }

    public function indexAction(Request $request)
    {
        $settingsForm = new FormBlueprint();
        $settingsForm->setName('avcms_settings');
        $settingsForm->setSuccessMessage("Settings Saved");
        $settingsModel = $this->model('Settings');

        $settingsManager = $this->get('settings_manager');

        $fields = [];
        foreach ($settingsManager->getFields() as $name => $field) {
            if (!isset($field['type']) || $field['type'] != 'hidden') {
                $fields[$name] = $field;
            }
        }

        $settingsForm->createFieldsFromArray($fields);
        $settingsForm->createSectionsFromArray($settingsManager->getSections());

        $form = $this->buildForm($settingsForm);
        $form->mergeData($settingsModel->getSettings());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $settings = $form->getData();
                $settingsModel->saveSettings($settings);
            }

            return new JsonResponse(array(
                'form' => $form->createView()->getJsonResponseData()
            ));
        }

        return new Response($this->renderAdminSection(
                '@CmsFoundation/admin/settings.twig',
                array('form' => $form->createView())
            )
        );
    }
}
