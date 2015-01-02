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
            ->join($this->model('WallpaperCategories'), ['id', 'name', 'slug'])
            ->get();

        return new Response($this->render('@Wallpapers/browse_wallpapers.twig', array('wallpapers' => $wallpapers, 'total_pages' => $finder->getTotalPages(), 'current_page' => $finder->getCurrentPage(), 'page_type' => $pageType)));
    }

    public function wallpaperDetailsAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->findOne($slug)->modelJoin($this->model('WallpaperCategories'), ['slug', 'name'])->first();

        if (!$wallpaper) {
            throw $this->createNotFoundException('Wallpaper Not Found');
        }

        $resolutions = $this->container->get('wallpaper.resolutions_manager')->getResolutions();

        return new Response($this->render('@Wallpapers/wallpaper_details.twig', ['wallpaper' => $wallpaper, 'resolutions' => $resolutions]));
    }

    public function wallpaperPreviewAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->findOne($slug)->first();

        list($width, $height) = explode('x', $request->get('resolution'));

        if (!$width || !$height) {
            throw $this->createNotFoundException('Not a valid resolution');
        }

        if (!$wallpaper) {
            throw $this->createNotFoundException('Wallpaper Not Found');
        }

        return new Response($this->render('@Wallpapers/wallpaper_preview.twig', ['wallpaper' => $wallpaper, 'wallpaper_width' => $width, 'wallpaper_height' => $height]));
    }
}
