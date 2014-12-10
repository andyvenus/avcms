<?php
/**
 * User: Andy
 * Date: 17/08/2014
 * Time: 23:08
 */

namespace AVCMS\Core\View\Extension;

use Symfony\Component\HttpFoundation\RequestStack;

class SiteUrlTwigExtension extends \Twig_Extension
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
        $url = $this->request->getUriForPath('/');

        $basename = basename($url);

        if (strpos($basename, '.') !== false) {
            $url = str_replace($basename, '', $url);
        }

        return array(
            'site_url' => $url,
            'web_url' => $url.'web/'
        );
    }

    public function getName()
    {
        return 'site_url_extension';
    }
}