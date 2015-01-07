<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 15:06
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WallpaperModulesController extends Controller
{
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
        $ratings = $this->model('AVCMS\Bundles\LikeDislike\Model\Ratings');

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

        $wallpapers = $this->wallpapers->query()->whereIn('id', $ids)->get();

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
}
