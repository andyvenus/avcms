<?php
/**
 * User: Andy
 * Date: 13/12/14
 * Time: 22:35
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    private $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function browseWallpapersAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->wallpapers->find();
        $wallpapers = $finder->published()
            ->setResultsPerPage(20)
            ->setSearchFields(['name'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'tags' => null, 'search' => null))
            ->get();

        return new Response($this->render('@Wallpapers/browse_wallpapers.twig', array('wallpapers' => $wallpapers, 'total_pages' => $finder->getTotalPages(), 'current_page' => $finder->getCurrentPage(), 'page_type' => $pageType)));
    }
}
