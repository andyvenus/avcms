<?php
/**
 * User: Andy
 * Date: 11/04/2014
 * Time: 11:22
 */

namespace AVCMS\Core\Controller;

use AVCMS\Core\Controller\Event\AdminFilterEntityEvent;
use AVCMS\Core\Controller\Event\AdminSaveContentEvent;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Model\ContentModel;
use AVCMS\Core\Model\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    protected function renderAdminSection($template, $ajax_depth = null, $context = array())
    {
        $vars = $this->getSharedTemplateVars($ajax_depth);

        $context = array_merge($vars, $context);

        return $this->render($template, $context);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = array('ajax_depth' => $ajax_depth);

        if ($ajax_depth == 'editor') {
            return $template_vars;
        }
        return $template_vars;
    }

    protected function manage(Request $request, $template, $template_vars = array())
    {
        if ($request->get('ajax_depth') == 'editor') {
            return new Response('');
        }

        return new Response($this->renderAdminSection(
                '@admin/blog_browser.twig',
                $request->get('ajax_depth'),
                $template_vars)
        );
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
     * @param $template_vars
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return JsonResponse|Response
     */
    protected function edit(Request $request, Model $model, FormBlueprint $form_blueprint, $edit_redirect_url, $edit_template, $browser_template, $template_vars = array())
    {
        $entity = $model->getOneOrNew($request->get('id', 0));

        if (!$entity) {
            throw $this->createNotFoundException($model->getSingular().' not found');
        }

        $form = $this->buildForm($form_blueprint, $entity, $request);

        if ($form->isSubmitted()) {
            $id = null;
            if ($form->isValid()) {
                $this->filterValidEntity($entity);
                $id = $model->save($entity);

                $this->getEventDispatcher()->dispatch('admin.save.content', new AdminSaveContentEvent($entity, $model, $form));
            }

            return new JsonResponse(array(
                'form' => $form->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl($edit_redirect_url, array('id' => $id))
            ));
        }

        $template_vars = array_merge($template_vars,  array('item' => $entity, 'form' => $form->createView(), 'browser_template' => $browser_template));

        return new Response($this->renderAdminSection(
                $edit_template,
                $request->get('ajax_depth'),
                $template_vars)
        );
    }

    protected function delete(Request $request, Model $model)
    {
        if ($request->request->has('ids') && is_array($request->request->get('ids'))) {
            $ids = $request->request->get('ids');

            $filtered_ids = array();
            foreach ($ids as $id) {
                $filtered_ids[] = intval($id);
            }

            $model->deleteByIds($filtered_ids);
        }
        else if ($request->request->has('id')) {
            $model->deleteById($request->request->get('id'));
        }
        else {
            return new JsonResponse(array('success' => 0));
        }

        return new JsonResponse(array('success' => 1));
    }

    protected function togglePublished(Request $request, ContentModel $model)
    {
        $published = $request->request->get('published', 1);
        if ($published != 1 && $published != 0) {
            $published = 1;
        }

        if ($request->request->has('ids') && is_array($request->request->get('ids'))) {
            $ids = $request->request->get('ids');

            $filtered_ids = array();
            foreach ($ids as $id) {
                $filtered_ids[] = intval($id);
            }

            $model->setPublished($filtered_ids, $published);
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
}