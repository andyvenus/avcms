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
    public function homepageAction($modulePosition, $title = null, $fullTitle = false)
    {
        if ($modulePosition === 'homepage') {
            $fullTitle = true;
            $title = $this->setting('site_name');

            if ($tagLine = $this->setting('site_tagline')) {
                $title .= ' - '.$tagLine;
            }
        }

        return new Response($this->render('@CmsFoundation/homepage.twig', ['module_position' => $modulePosition, 'homepage_title' => $title, 'full_title' => $fullTitle]));
    }
}
