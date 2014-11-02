<?php
/**
 * User: Andy
 * Date: 02/11/14
 * Time: 15:45
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

class PaginationTwigExtension extends \Twig_Extension
{
    protected $template = '@CmsFoundation/pagination.twig';

    /**
     * @var \Twig_Environment
     */
    protected $environment;

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

    public function pagination($pages, $currentPage, $route, $routeAttr = [])
    {
        return $this->environment->render($this->template, [
            'total_pages' => $pages,
            'current_page' => $currentPage,
            'route' => $route,
            'attributes' => $routeAttr
        ]);
    }
}