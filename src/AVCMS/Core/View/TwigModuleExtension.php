<?php
/**
 * User: Andy
 * Date: 18/12/2013
 * Time: 11:43
 */

namespace AVCMS\Core\View;


class TwigModuleExtension extends \Twig_Extension {

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_modules';
    }
}