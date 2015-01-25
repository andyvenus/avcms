<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 14:12
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MenuModulesController extends Controller
{
    public function menuModule($module, $userSettings)
    {
        $menu = $userSettings['menu'];

        if ($userSettings['type'] === 'buttons') {
            $template = '@CmsFoundation/module/button_menu_module.twig';
        }
        else {
            $template = '@CmsFoundation/module/list_menu_module.twig';
        }

        return new Response($this->render($template, ['menu' => $menu, 'user_settings' => $userSettings]));
    }
}
