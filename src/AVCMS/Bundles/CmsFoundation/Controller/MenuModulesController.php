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

        return new Response($this->render('@CmsFoundation/module/menu_module.twig', ['menu' => $menu]));
    }
}
