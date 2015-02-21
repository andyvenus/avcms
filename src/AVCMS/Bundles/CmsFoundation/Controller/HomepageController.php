<?php
/**
 * User: Andy
 * Date: 21/02/15
 * Time: 13:58
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    public function homepageAction($modulePosition, $title = null)
    {
        return new Response($this->render('@CmsFoundation/homepage.twig', ['module_position' => $modulePosition, 'title' => $title]));
    }
}
