<?php
/**
 * User: Andy
 * Date: 17/09/2014
 * Time: 12:53
 */

namespace AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider;

use AVCMS\Core\Form\ChoicesProviderInterface;
use Symfony\Component\Routing\RouterInterface;

class RouteChoicesProvider implements ChoicesProviderInterface
{
    protected $router;

    protected $limitEnvironment;

    public function __construct(RouterInterface $router, $limitEnvironment = null)
    {
        $this->router = $router;
        $this->limitEnvironment = $limitEnvironment;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $choices = array();
        foreach ($this->router->getRouteCollection()->all() as $routeName => $route) {
            if (!$this->limitEnvironment || $this->limitEnvironment == $route->getDefault('_avcms_env')) {
                $choices[$routeName] = $routeName . ' - ' . $route->getPath();
            }
        }

        return $choices;
    }
}