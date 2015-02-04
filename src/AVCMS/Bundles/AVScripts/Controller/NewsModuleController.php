<?php
/**
 * User: Andy
 * Date: 04/02/15
 * Time: 10:28
 */

namespace AVCMS\Bundles\AVScripts\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NewsModuleController extends Controller
{
    public function AVSNewsModule()
    {
        return new Response($this->render('@AVScripts/module/avs_news_module.twig'));
    }
}
