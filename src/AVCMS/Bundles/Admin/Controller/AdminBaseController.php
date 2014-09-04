<?php
/**
 * User: Andy
 * Date: 11/04/2014
 * Time: 11:22
 */

namespace AVCMS\Bundles\Admin\Controller;

use AVCMS\Core\Content\EditContentHelper;
use AVCMS\Core\Controller\Controller;
use AVCMS\Bundles\Admin\Event\AdminEditFormBuiltEvent;
use AVCMS\Bundles\Admin\Event\AdminFilterEntityEvent;
use AVCMS\Bundles\Admin\Event\AdminSaveContentEvent;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Model\ContentModel;
use AVCMS\Core\Model\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminBaseController extends Controller
{

    protected function renderAdminSection($template, $ajaxDepth = null, $context = array())
    {
        $vars = $this->getSharedTemplateVars($ajaxDepth);

        $context = array_merge($vars, $context);

        return $this->render($template, $context);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = array('ajax_depth' => $ajaxDepth);

        if (isset($this->browser_template)) {
            $templateVars['browser_template'] = $this->browser_template;
        }

        if ($ajaxDepth == 'editor') {
            return $templateVars;
        }
        return $templateVars;
    }

    protected function createManageResponse(Request $request, $template, $templateVars = array())
    {
        if ($request->get('ajax_depth') == 'editor') {
            return new Response('');
        }

        return new Response($this->renderAdminSection(
            $template,
            $request->get('ajax_depth'),
            $templateVars
        ));
    }

    /**
     * General all purpose edit item
     *
     * @param Request $request
     * @param Model $model
     * @param FormBlueprint $form_blueprint
     * @param string $edit_redirect_url
     * @param string $edit_template
     * @param string $browser_template
     * @param array $template_vars
     * @param null $entity
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse|Response
     * @depreciated
     */
    protected function edit(Request $request, Model $model, FormBlueprint $form_blueprint, $edit_redirect_url, $edit_template, $browser_template, $template_vars = array(), $entity = null)
    {
        if (!$entity) {
            $entity = $model->getOneOrNew($request->attributes->get('id', 0));

            if (!$entity) {
                throw $this->createNotFoundException($model->getSingular().' not found');
            }
        }

        $form = $this->buildForm($form_blueprint, $request, $entity);

        $this->getEventDispatcher()->dispatch('admin.edit.form.built', new AdminEditFormBuiltEvent($entity, $model, $form, $request));

        if ($form->isSubmitted()) {
            $id = 0;
            if ($form->isValid()) {
                $form->saveToEntities();
                $this->filterValidEntity($entity);
                $id = $model->save($entity);

                $this->getEventDispatcher()->dispatch('admin.save.content', new AdminSaveContentEvent($entity, $model, $form, $request));
            }

            return new JsonResponse(array(
                'form' => $form->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl($edit_redirect_url, array('id' => $id)),
                'id' => $id
            ));
        }

        $template_vars = array_merge($template_vars,  array('item' => $entity, 'form' => $form->createView(), 'browser_template' => $browser_template));

        return new Response($this->renderAdminSection(
                $edit_template,
                $request->get('ajax_depth'),
                $template_vars)
        );
    }

    protected function editContentHelper(Model $model, FormHandler $form, $entity = null)
    {
        return new EditContentHelper($model, $form, $entity);
    }


    protected function createEditResponse(EditContentHelper $helper, $request, $template, $browserTemplate, $successRedirect, $templateVars = array())
    {
        if ($helper->formSubmitted()) {

            if (!is_array($successRedirect)) {
                $redirectRoute = $successRedirect;
                $redirectParams = array();
            }
            else {
                $redirectParams = $successRedirect[1];
                $redirectRoute = $successRedirect[0];
            }

            $redirectUrl = $this->generateUrl($redirectRoute, $redirectParams);

            return new JsonResponse($helper->jsonResponseData($redirectUrl));
        }
        else {
            $templateVars = array_merge(array('item' => $helper->getEntity(), 'form' => $helper->getForm()->createView(), 'browser_template' => $browserTemplate), $templateVars);

            return new Response($this->renderAdminSection(
                $template,
                $request->get('ajax_depth'),
                $templateVars
            ));
        }
    }

    protected function delete($ids, Model $model)
    {
        if (!$ids) {
            return array('success' => 0, 'error' => 'No ids set');
        }

        $model->deleteById($ids);

        return array('success' => 1);
    }

    protected function togglePublished(Request $request, ContentModel $model)
    {
        if ($this->checkCsrfToken($request) === false) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $published = $request->request->get('published', 1);
        if ($published != 1 && $published != 0) {
            $published = 1;
        }

        if ($request->request->has('ids') && is_array($request->request->get('ids'))) {
            $ids = $request->request->get('ids');

            $filteredIds = array();
            foreach ($ids as $id) {
                $filteredIds[] = intval($id);
            }

            $model->setPublished($filteredIds, $published);
        }
        else if ($request->request->has('id')) {
            $model->setPublished($request->request->get('id'), $published);
        }
        else {
            return new JsonResponse(array('success' => 0));
        }

        return new JsonResponse(array('success' => 1));
    }

    protected function filterValidEntity($entity)
    {
        $this->container->get('dispatcher')->dispatch('admin.controller.filter.entity', new AdminFilterEntityEvent($entity));

        return $entity;
    }

    protected function checkCsrfToken(Request $request)
    {
        $token_manager = $this->container->get('csrf.token');

        return $token_manager->checkToken($request->get('_csrf_token'));
    }

    protected function invalidCsrfTokenJsonResponse()
    {
        return new JsonResponse(array('success' => 0, 'error' => 'Invalid CSRF Token', 'error_code' => 'invalid_csrf_token'));
    }
}