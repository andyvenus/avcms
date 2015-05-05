<?php
/**
 * User: Andy
 * Date: 05/05/15
 * Time: 13:31
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

class PhpIncludeTwigExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'include_php' => new \Twig_SimpleFunction('include_php',
                array($this, 'includePhp'),
                array('is_safe' => array('html')
                )
            )
        );
    }

    public function includePhp($file)
    {
        include $file;
    }

    public function getName()
    {
        return 'avcms-include-php';
    }
}
