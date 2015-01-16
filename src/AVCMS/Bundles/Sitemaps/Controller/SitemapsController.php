<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 19:34
 */

namespace AVCMS\Bundles\Sitemaps\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SitemapsController extends Controller
{
    public function sitemapAction()
    {
        $smm = $this->container->get('sitemaps_manager');
        //$smm->addSitemap(new WallpapersSitemap($this->model('AVCMS\Bundles\Wallpapers\Model\Wallpapers'), $this->container->get('router')));

        $smm->writeSitemaps();

        return new Response($smm->getIndexFileContents(), 200, ['Content-Type' => 'application/xml']);
    }
}
