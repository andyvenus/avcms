<?php
/**
 * User: Andy
 * Date: 28/05/15
 * Time: 17:19
 */

namespace AVCMS\Bundles\NextPrevMod\Controller;

use AVCMS\Bundles\Wallpapers\Model\Wallpaper;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NextPrevController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    private $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function nextPrevWallpaperButtonsModule(Wallpaper $wallpaper, $width, $height)
    {
        $mainQuery = $this->wallpapers->query()
            ->where('original_width', '>=', $width)
            ->where('original_height', '>=', $height)
            ->where('category_id', $wallpaper->getCategoryId())
            ->limit(1)
        ;

        $nextQuery = clone $mainQuery;
        $nextQuery->where('id', '>', $wallpaper->getId());
        $nextQuery->orderBy('id');

        $nextWallpaper = $nextQuery->first();

        $prevQuery = clone $mainQuery;
        $prevQuery->where('id', '<', $wallpaper->getId());
        $prevQuery->orderBy('id', 'DESC');

        $prevWallpaper = $prevQuery->first();

        return new Response($this->render('@NextPrevMod/next_prev_wallpapers_module.twig', [
            'next' => $nextWallpaper,
            'prev' => $prevWallpaper,
            'resolution' => $width.'x'.$height
        ]));
    }
}
