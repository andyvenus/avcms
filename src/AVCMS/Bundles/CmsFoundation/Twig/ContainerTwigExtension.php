<?php
/**
 * User: Andy
 * Date: 11/06/2017
 * Time: 18:43
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerTwigExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'include_php' => new \Twig_SimpleFunction('service',
                array($this, 'service')
            )
        );
    }

    public function service($id)
    {
        return $this->container->get($id);
    }

    public function getName()
    {
        return 'avcms-service';
    }
}
