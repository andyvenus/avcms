<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 15:06
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\Response;

class WallpaperModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    private $wallpapers;

    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\WallpaperCategories
     */
    private $wallpaperCategories;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
        $this->wallpaperCategories = $this->model('WallpaperCategories');
    }

    public function wallpapersModule($adminSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->wallpapers->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

        if ($adminSettings['category']) {
            $category = $this->wallpaperCategories->getOne($adminSettings['category']);

            if ($category) {
                $query->category($category);
            }
        }

        if ($adminSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_wallpapers'), 'label' => 'All Featured Wallpapers'];
        }
        elseif ($adminSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'wallpaper');
            $query->ids($ids, 'wallpapers.id');
            $moreButton = ['url' => $this->generateUrl('liked_wallpapers', ['filter_user' => $user->getSlug()]), 'label' => 'All Liked Wallpapers'];
        }
        elseif ($adminSettings['filter'] === 'submitted') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $query->author($user->getId());
            $moreButton = ['url' => $this->generateUrl('submitted_wallpapers', ['filter_user' => $user->getSlug()]), 'label' => 'All Submitted Wallpapers'];
        }
        else {
            if ($adminSettings['more_button_start_page'] == 2) {
                $pageTwoExists = ($query->getQuery()->count() > $this->setting('browse_wallpapers_per_page'));
                if (!$pageTwoExists) {
                    $adminSettings['more_button_start_page'] = 1;
                }
            }

            $label = 'Next Page';
            if ($adminSettings['more_button_start_page'] == 1) {
                $label = 'More';
            }

            $moreButtonAttr = [
                'page' => $adminSettings['more_button_start_page'],
                'order' => $adminSettings['order']
            ];

            if (isset($category)) {
                $moreButtonRoute = 'wallpaper_category';
                $moreButtonAttr['category'] = $category->getSlug();
            } else {
                $moreButtonRoute = 'browse_wallpapers';
            }

            $moreButton = ['url' => $this->generateUrl($moreButtonRoute, $moreButtonAttr), 'label' => $label];
        }

        if ($adminSettings['show_wallpaper_category']) {
            $query->join($this->model('WallpaperCategories'), ['name', 'slug']);
        }

        $wallpapers = $query->get();

        if ($adminSettings['layout'] === 'list') {
            $template = 'wallpapers_list_module.twig';
        }
        else {
            $template = 'wallpapers_thumbnail_module.twig';
        }

        return new Response($this->render('@Wallpapers/module/'.$template, array(
            'wallpapers' => $wallpapers,
            'admin_settings' => $adminSettings,
            'columns' => $adminSettings['columns'],
            'more_button' => $moreButton
        )));
    }

    public function resolutionsModule()
    {
        $resCategories = $this->container->get('wallpaper.resolutions_manager')->getAllResolutions();

        return new Response($this->render('@Wallpapers/module/resolutions_module.twig', ['resolution_categories' => $resCategories]));
    }

    public function tagsModule($adminSettings)
    {
        return $this->getTagsModule($adminSettings, 'wallpaper', 'wallpaper_tag', 'tags');
    }

    public function wallpaperStatsModule()
    {
        $totalWallpapers = $this->wallpapers->query()->count();
        $totalHits = $this->wallpapers->query()->select([$this->wallpapers->query()->raw('SUM(hits) as total_hits')])->first(\PDO::FETCH_ASSOC)['total_hits'];
        $totalDownloads = $this->wallpapers->query()->select([$this->wallpapers->query()->raw('SUM(total_downloads) as total_downloads')])->first(\PDO::FETCH_ASSOC)['total_downloads'];
        $totalLikes = $this->wallpapers->query()->select([$this->wallpapers->query()->raw('SUM(likes) as total_likes')])->first(\PDO::FETCH_ASSOC)['total_likes'];

        return new Response($this->render('@Wallpapers/module/wallpaper_stats_module.twig', [
            'total_wallpapers' => $totalWallpapers,
            'total_hits' => $totalHits,
            'total_downloads' => $totalDownloads,
            'total_likes' => $totalLikes
        ]));
    }
}
