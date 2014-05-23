<?php
/**
 * User: Andy
 * Date: 11/04/2014
 * Time: 11:22
 */

namespace AVCMS\Core\Controller;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Model\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    protected function renderAdminSection($template, $ajax_depth = null, $context = array())
    {
        $vars = $this->getIndexTemplateVars($ajax_depth);

        $context = array_merge($vars, $context);

        return $this->render($template, $context);
    }

    protected function getIndexTemplateVars($ajax_depth)
    {
        $template_vars = array('ajax_depth' => $ajax_depth);

        if ($ajax_depth == 'editor') {
            return $template_vars;
        }
        return $template_vars;
    }

    // TODO: Change / Delete

    /**
     * General all purpose edit item
     *
     * @param Request $request
     * @param Model $model
     * @param FormBlueprint $form_blueprint
     * @param string $edit_template
     * @param string $browser_template
     * @param string $edit_redirect_url
     * @return JsonResponse|Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function editItem(Request $request, Model $model, FormBlueprint $form_blueprint, $edit_template, $browser_template, $edit_redirect_url)
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
            }

            return new JsonResponse(array(
                'form' => $form->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl($edit_redirect_url, array('id' => $id))
            ));
        }

        return new Response($this->renderAdminSection(
                $edit_template,
                $request->get('ajax_depth'),
                array('item' => $entity, 'form' => $form->createView(), 'browser_template' => $browser_template))
        );
    }

    protected function filterValidEntity($entity)
    {
        return $entity;
    }
}