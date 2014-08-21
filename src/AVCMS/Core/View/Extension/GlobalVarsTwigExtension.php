<?php
/**
 * User: Andy
 * Date: 17/08/2014
 * Time: 23:08
 */

namespace AVCMS\Core\View\Extension;

use Symfony\Component\HttpFoundation\RequestStack;

class GlobalVarsTwigExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __construct(RequestStack $request_stack)
    {
        $this->request = $request_stack->getMasterRequest();
    }

    public function getGlobals()
    {
        return array(
            'site_url' => $this->request->getUriForPath('/')
        );
    }

    public function getName()
    {
        return 'avcms_globals';
    }
}