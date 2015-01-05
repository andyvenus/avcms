<?php
/**
 * User: Andy
 * Date: 02/11/14
 * Time: 15:45
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class PaginationTwigExtension extends \Twig_Extension
{
    protected $template = '@CmsFoundation/pagination.twig';

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return array(
            'pagination' => new \Twig_SimpleFunction('pagination',
                array($this, 'pagination'),
                array('is_safe' => array('html')
                )
            )
        );
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'avcms_pagination_extension';
    }

    public function pagination($pages, $currentPage, $route = null, $routeAttr = [])
    {
        $requestAttr = $this->requestStack->getCurrentRequest()->query->all();

        $attr = $this->requestStack->getCurrentRequest()->attributes->all();
        foreach ($attr as $index => $val) {
            if (strpos($index, '_') === 0) {
                unset($attr[$index]);
            }
        }

        $routeAttr = array_merge($routeAttr, $attr, $requestAttr);

        if ($route === null) {
            $route = $this->requestStack->getCurrentRequest()->get('_route');
        }

        return $this->environment->render($this->template, [
            'total_pages' => $pages,
            'current_page' => $currentPage,
            'route' => $route,
            'attributes' => $routeAttr
        ]);
    }
}
