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

    /**
     * Renders an admin template with the shared context vars
     *
     * @param $template
     * @param null $ajaxDepth
     * @param array $context
     * @return string|Response
     */
    protected function renderAdminSection($template, $ajaxDepth = null, $context = array())
    {
        $vars = $this->getSharedTemplateVars($ajaxDepth);

        $context = array_merge($vars, $context);

        return $this->render($template, $context);
    }

    /**
     * Get the shared template context and automatically set the browser template if set
     *
     * @param $ajaxDepth
     * @return array
     */
    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = array('ajax_depth' => $ajaxDepth);

        if (isset($this->browserTemplate)) {
            $templateVars['browser_template'] = $this->browserTemplate;
        }

        if ($ajaxDepth == 'editor') {
            return $templateVars;
        }
        return $templateVars;
    }

    /**
     * Handle a simple request for a manage content section
     *
     * @param Request $request
     * @param $template
     * @param array $templateVars
     * @return Response
     */
    protected function handleManage(Request $request, $template, $templateVars = array())
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
     * Handle editing a content item and return a response
     *
     * @param Request $request
     * @param Model $model
     * @param FormBlueprint $formBlueprint
     * @param string $editRedirectUrl
     * @param string $editTemplate
     * @param string $browserTemplate
     * @param array $templateVars
     * @param null $entity
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse|Response
     * @depreciated
     */
    protected function handleEdit(Request $request, Model $model, FormBlueprint $formBlueprint, $editRedirectUrl, $editTemplate, $browserTemplate, $templateVars = array(), $entity = null)
    {
        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($model, $form, $entity);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException(ucfirst($model->getSingular()).' not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        return $this->createEditResponse(
            $helper,
            $request,
            $editTemplate,
            $browserTemplate,
            array($editRedirectUrl, array('id' => $id)),
            $templateVars
        );
    }

    /**
     * Get an edit content helper
     *
     * @param Model $model
     * @param FormHandler $form
     * @param null $entity
     * @return EditContentHelper
     */
    protected function editContentHelper(Model $model, FormHandler $form, $entity = null)
    {
        $eventDispatcher = $this->container->get('dispatcher');
        return new EditContentHelper($model, $form, $entity, $eventDispatcher);
    }

    /**
     * Create a response for editing content. Returns a JsonResponse when the form is submitted.
     *
     * @param EditContentHelper $helper
     * @param $request
     * @param $template
     * @param $browserTemplate
     * @param $successRedirect
     * @param array $templateVars
     * @return JsonResponse|Response
     */
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
            $templateVars = array_merge(array(
                'item' => $helper->getEntity(),
                'form' => $helper->getForm()->createView(),
                'browser_template' => $browserTemplate
            ), $templateVars);

            return new Response($this->renderAdminSection(
                $template,
                $request->get('ajax_depth'),
                $templateVars
            ));
        }
    }

    /**
     * Handle deletion of one or more items, return a JsonResponse indicating
     * whether it was successful
     *
     * @param $request
     * @param Model $model
     * @return JsonResponse
     */
    protected function handleDelete($request, Model $model)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $ids = $request->request->get('ids');

        if (!$ids) {
            return new JsonResponse(array('success' => 0, 'error' => 'No ids set'));
        }

        $model->deleteById($ids);

        return new JsonResponse(array('success' => 1));
    }

    /**
     * Handle toggling one or more items published
     *
     * @param Request $request
     * @param ContentModel $model
     * @return JsonResponse
     */
    protected function handleTogglePublished(Request $request, ContentModel $model)
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

    /**
     * @param Request $request
     * @return bool
     */
    protected function checkCsrfToken(Request $request)
    {
        $tokenManager = $this->container->get('csrf.token');

        return $tokenManager->checkToken($request->get('_csrf_token'));
    }

    /**
     * @return JsonResponse
     */
    protected function invalidCsrfTokenJsonResponse()
    {
        return new JsonResponse(array('success' => 0, 'error' => 'Invalid CSRF Token', 'error_code' => 'invalid_csrf_token'));
    }
}