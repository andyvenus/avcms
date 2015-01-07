<?php
/**
 * User: Andy
 * Date: 13/12/14
 * Time: 22:35
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Wallpapers\Form\WallpaperFrontendFiltersForm;
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

    public function wallpapersHomeAction(Request $request)
    {
        return new Response($this->render('@Wallpapers/wallpapers_home.twig'));
    }

    public function browseWallpapersAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->wallpapers->find();
        $query = $finder->published()
            ->setResultsPerPage(12)
            ->setSearchFields(['name'])
            ->handleRequest($request, array(
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
                'resolution' => null
            ));

        $category = null;
        if ($request->get('category') !== null) {
            $categories = $this->model('WallpaperCategories');
            $category = $categories->findOne($request->get('category'))->first();

            if (!$category) {
                throw $this->createNotFoundException();
            }

            $categoryId = $category->getId();

            if ($category->getParent()) {
                $category->parent = $categories->getOne($category->getParent());
            }
            else {
                $category->subcategories = $categories->query()->where('parent', $categoryId)->get();
            }

            $query->getQuery()->where(function($q) use ($categoryId) {
                $q->where('category_id', $categoryId)->orWhere('category_parent_id', $categoryId);
            });
        }

        $wallpapers = $query->get();

        $formBp = new WallpaperFrontendFiltersForm($this->get('wallpaper.resolutions_manager')->getAllResolutions());
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));
        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Wallpapers/browse_wallpapers.twig', array(
            'wallpapers' => $wallpapers,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'filters_form' => $filtersForm->createView(),
            'finder_request' => $finder->getRequestFilters()
        )));
    }

    public function wallpaperDetailsAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model('WallpaperCategories'), ['id', 'slug', 'name'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$wallpaper) {
            throw $this->createNotFoundException('Wallpaper Not Found');
        }

        $resolutions = $this->container->get('wallpaper.resolutions_manager')->getWallpaperResolutions($wallpaper);

        $this->container->get('hitcounter')->registerHit($this->wallpapers, $wallpaper->getId());

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
