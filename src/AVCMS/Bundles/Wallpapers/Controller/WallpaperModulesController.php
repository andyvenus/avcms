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
use Symfony\Component\HttpFoundation\Response;

class WallpaperModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    private $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function wallpapersListModule($module, $userSettings)
    {
        $moreButton = null;

        $query = $this->wallpapers->find()
            ->limit($userSettings['limit'])
            ->order($userSettings['order'])
            ->published()
            ->join($this->model('WallpaperCategories'), ['id', 'name', 'slug']);

        if ($userSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_wallpapers'), 'label' => 'All Featured Wallpapers'];
        }

        $wallpapers = $query->get();

        $columns = ($userSettings['columns'] ? $userSettings['columns'] : 1);

        if ($userSettings['layout'] === 'thumbnails') {
            $template = 'wallpapers_thumbnail_module.twig';
        }
        else {
            $template = 'wallpapers_list_module.twig';
        }

        return new Response($this->render('@Wallpapers/module/'.$template, array(
            'wallpapers' => $wallpapers,
            'user_settings' => $userSettings,
            'columns' => $columns,
            'more_button' => $moreButton
        )));
    }

    public function likedWallpapersModule($userSettings, User $user)
    {
        $ratings = $this->model('LikeDislike:Ratings');

        $liked = $ratings->query()
            ->where('user_id', $user->getId())
            ->where('content_type', 'wallpaper')
            ->where('rating', 1)
            ->select(['content_id'])
            ->limit($userSettings['limit'])
        ->get();

        $ids = [];
        foreach ($liked as $like) {
            $ids[] = $like->getContentId();
        }

        if (!empty($ids)) {
            $wallpapers = $this->wallpapers->query()->whereIn('id', $ids)->get();
        }
        else {
            $wallpapers = [];
        }

        $columns = ($userSettings['columns'] ? $userSettings['columns'] : 1);

        if ($userSettings['layout'] === 'thumbnails') {
            $template = 'wallpapers_thumbnail_module.twig';
        }
        else {
            $template = 'wallpapers_list_module.twig';
        }

        return new Response($this->render('@Wallpapers/module/'.$template, array(
            'wallpapers' => $wallpapers,
            'user_settings' => $userSettings,
            'columns' => $columns,
        )));
    }

    public function resolutionsModule()
    {
        $resCategories = $this->container->get('wallpaper.resolutions_manager')->getAllResolutions();

        return new Response($this->render('@Wallpapers/module/resolutions_module.twig', ['resolution_categories' => $resCategories]));
    }

    public function tagsModule($userSettings)
    {
        return $this->getTagsModule($userSettings, 'wallpaper', 'browse_wallpapers', 'ids');
    }
}
