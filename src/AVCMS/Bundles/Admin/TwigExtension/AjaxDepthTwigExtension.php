<?php
/**
 * User: Andy
 * Date: 05/02/15
 * Time: 10:56
 */

namespace AVCMS\Bundles\Admin\TwigExtension;

use Symfony\Component\HttpFoundation\RequestStack;

class AjaxDepthTwigExtension extends \Twig_Extension
{
    private $ajaxDepth;

    public function __construct(RequestStack $requestStack)
    {
        $this->ajaxDepth = $requestStack->getMasterRequest()->get('ajax_depth');
    }

    public function getGlobals()
    {
        return ['ajax_depth' => $this->ajaxDepth];
    }

    public function getName()
    {
        return 'admin_ajax_depth';
    }
}
