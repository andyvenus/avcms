<?php
/**
 * User: Andy
 * Date: 09/11/14
 * Time: 19:03
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class RouteNameTwigExtension extends \Twig_Extension
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getGlobals()
    {
        return array(
            'current_route' => $this->requestStack->getCurrentRequest()->get('_route')
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_route_name';
    }
}
