<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 14:22
 */

namespace AVCMS\Bundles\Pages\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Pages\Model\Pages
     */
    private $pages;

    public function setUp()
    {
        $this->pages = $this->model('Pages');
    }

    public function pageAction($slug)
    {
        $page = $this->pages->find()->slug($slug)->first();

        if (!$page) {
            throw $this->createNotFoundException();
        }

        $this->container->get('hitcounter')->registerHit($this->pages, $page->getId());

        return new Response($this->render('@Pages/page.twig', ['page' => $page]));
    }
}
