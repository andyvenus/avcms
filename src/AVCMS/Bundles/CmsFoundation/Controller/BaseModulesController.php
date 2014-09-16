<?php
/**
 * User: Andy
 * Date: 15/09/2014
 * Time: 11:02
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseModulesController extends Controller
{
    public function firstModuleAction($module) {
        return new Response('Here is the content: '.$module->getTitle());
    }
}