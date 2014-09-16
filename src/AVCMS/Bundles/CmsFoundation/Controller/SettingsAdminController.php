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
        $settings_form = new FormBlueprint();
        $settings_form->setSuccessMessage("Settings Saved");
        $settings_model = $this->model('Settings');

        $settings_manager = $this->get('settings_manager');

        $settings_form->createFieldsFromArray($settings_manager->getFields());
        $settings_form->createSectionsFromArray($settings_manager->getSections());

        $form = $this->buildForm($settings_form);
        $form->mergeData($settings_model->getSettings());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $settings = $form->getData();
                $settings_model->saveSettings($settings);
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