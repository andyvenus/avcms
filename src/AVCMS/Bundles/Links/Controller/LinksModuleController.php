<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 12:49
 */

namespace AVCMS\Bundles\Links\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LinksModuleController extends Controller
{
    public function linksModule($adminSettings)
    {
        $links = $this->model('Links')
            ->getTopLinksFinder($this->model('Referrals'), 1, $adminSettings['total_links'])
        ->get();

        return new Response($this->render('@Links/module/links_module.twig', ['links' => $links]));
    }
}
