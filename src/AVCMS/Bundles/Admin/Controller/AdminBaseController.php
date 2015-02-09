<?php
/**
 * User: Andy
 * Date: 11/04/2014
 * Time: 11:22
 */

namespace AVCMS\Bundles\Admin\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormHandler;
use AV\Model\Model;
use AVCMS\Bundles\Admin\Event\AdminDeleteEvent;
use AVCMS\Bundles\Admin\Event\AdminTogglePublishedEvent;
use AVCMS\Core\Content\EditContentHelper;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminBaseController extends Controller
{
    /**
     * @var string
     */
    protected $browserTemplate;

    /**
     * Renders an admin template with the shared context vars
     *
     * @param $template string #Template
     * @param array $context
     * @return string
     */
    protected function renderAdminSection($template, $context = array())
    {
        $vars = $this->getSharedTemplateVars();

        $context = array_merge($vars, $context);

        return $this->render($template, $context);
    }

    /**
     * Get the shared template context and automatically set the browser template if set
     *
     * @return array
     */
    protected function getSharedTemplateVars()
    {
        $templateVars = [];

        if (isset($this->browserTemplate)) {
            $templateVars['browser_template'] = $this->browserTemplate;
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

        $templateVars['finder'] = $request->query->get('finder', []);

        if ($request->query->has('id')) {
            $templateVars['finder']['id'] = $request->query->get('id');
        }

        return new Response($this->renderAdminSection(
            $template,
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
     * @param array $templateVars
     * @param null $entity
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse|Response
     * @depreciated
     */
    protected function handleEdit(Request $request, Model $model, FormBlueprint $formBlueprint, $editRedirectUrl, $editTemplate, $templateVars = array(), $entity = null)
    {
        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($model, $form, $entity);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException(ucwords(str_replace('_', ' ', $model->getSingular())).' not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        return $this->createEditResponse(
            $helper,
            $editTemplate,
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
    protected function editContentHelper(Model $model, FormHandler $form = null, $entity = null)
    {
        $eventDispatcher = $this->container->get('dispatcher');
        return new EditContentHelper($model, $form, $entity, $eventDispatcher);
    }

    /**
     * Create a response for editing content. Returns a JsonResponse when the form is submitted.
     *
     * @param EditContentHelper $helper
     * @param $template
     * @param $successRedirect
     * @param array $templateVars
     * @return JsonResponse|Response
     */
    protected function createEditResponse(EditContentHelper $helper, $template, $successRedirect, $templateVars = array())
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
            ), $templateVars);

            return new Response($this->renderAdminSection(
                $template,
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

        $this->dispatchEvent('admin.delete', new AdminDeleteEvent($request, $model, (array) $ids));

        return new JsonResponse(array('success' => 1));
    }

    /**
     * Handle toggling one or more items published
     *
     * @param Request $request
     * @param Model $model
     * @param string $column
     * @return JsonResponse
     */
    protected function handleTogglePublished(Request $request, Model $model, $column = 'published')
    {
        if ($this->checkCsrfToken($request) === false) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $published = $request->request->get($column, null);
        if ($published === null) {
            $published = $request->request->get('published', '1');
        }
        if ($published !== '1' && $published !== '0') {
            $published = '1';
        }

        if ($request->request->has('ids') && is_array($request->request->get('ids'))) {
            $ids = $request->request->get('ids');

            $filteredIds = array();
            foreach ($ids as $id) {
                $filteredIds[] = intval($id);
            }

            $model->query()->whereIn('id', $filteredIds)->update([$column => $published]);
        }
        else if ($request->request->has('id')) {
            $model->query()->where('id', $request->request->get('id'))->update([$column => $published]);
        }
        else {
            return new JsonResponse(array('success' => 0));
        }

        $this->dispatchEvent('admin.toggle_published', new AdminTogglePublishedEvent($request, $model, $column));

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
