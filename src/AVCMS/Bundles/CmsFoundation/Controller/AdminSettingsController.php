<?php
/**
 * User: Andy
 * Date: 13/08/2014
 * Time: 10:41
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Bundles\Admin\Controller\AdminController;
use AVCMS\Bundles\CmsFoundation\Form\SettingsForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingsController extends AdminController
{
    public function indexAction(Request $request)
    {
        $settings_form = new SettingsForm();
        $settings_model = $this->model('Settings');

        $bundle_manager = $this->get('bundle_manager');
        $bundle_configs = $bundle_manager->getBundleConfigs();

        foreach ($bundle_configs as $bundle_config) {
            if ($bundle_settings = $bundle_config['user_settings']) {
                $settings_form->createSettingsFieldsFromArray($bundle_settings);

                if ($bundle_sections = $bundle_config['user_settings_sections']) {
                    foreach ($bundle_config['user_settings_sections'] as $id => $label) {
                        $settings_form->addSection($id, $label);
                    }
                }
            }
        }

        $form = $this->buildForm($settings_form);
        $form->mergeData($settings_model->getSettings());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $settings = $form->getData();
                unset($settings['_csrf_token']);

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