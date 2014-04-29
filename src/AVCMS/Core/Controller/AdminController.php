<?php
/**
 * User: Andy
 * Date: 11/04/2014
 * Time: 11:22
 */

namespace AVCMS\Core\Controller;

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
}