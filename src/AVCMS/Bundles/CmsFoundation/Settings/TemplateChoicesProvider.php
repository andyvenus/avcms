<?php
/**
 * User: Andy
 * Date: 16/08/2014
 * Time: 10:54
 */

namespace AVCMS\Bundles\CmsFoundation\Settings;

use AVCMS\Core\Form\ChoicesProviderInterface;

class TemplateChoicesProvider implements ChoicesProviderInterface
{
    public function getChoices()
    {
        $template_dir = 'templates/frontend';

        $dirs = scandir($template_dir);
        $choices = array();
        foreach ($dirs as $dir) {
            if (is_dir($template_dir.'/'.$dir) && $dir != '.' && $dir != '..') {
                $choices['templates/frontend/'.$dir] = $dir;
            }
        }

        return $choices;
    }
}