<?php
/**
 * User: Andy
 * Date: 16/08/2014
 * Time: 10:54
 */

namespace AVCMS\Bundles\CmsFoundation\Settings;

use AV\Form\ChoicesProviderInterface;

class TemplateChoicesProvider implements ChoicesProviderInterface
{
    public function getChoices()
    {
        $template_dir = 'webmaster/templates/frontend'; //todo: remove hardcode

        $dirs = scandir($template_dir);
        $choices = array();
        foreach ($dirs as $dir) {
            if (is_dir($template_dir.'/'.$dir) && $dir != '.' && $dir != '..' && file_exists($template_dir.'/'.$dir.'/template.yml')) {
                $choices['webmaster/templates/frontend/'.$dir] = $dir;
            }
        }

        return $choices;
    }
}
