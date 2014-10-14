<?php
/**
 * User: Andy
 * Date: 13/08/2014
 * Time: 10:41
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\CmsFoundation\Form\SettingsForm;
use AVCMS\Core\Form\FormBlueprint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsAdminController extends AdminBaseController
{
    public function indexAction(Request $request)
    {
        $settingsForm = new FormBlueprint();
        $settingsForm->setName('avcms_settings');
        $settingsForm->setSuccessMessage("Settings Saved");
        $settingsModel = $this->model('Settings');

        $settingsManager = $this->get('settings_manager');

        $settingsForm->createFieldsFromArray($settingsManager->getFields());
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
                '@CmsFoundation/settings.twig',
                $request->get('ajax_depth'),
                array('form' => $form->createView())
            )
        );
    }
}