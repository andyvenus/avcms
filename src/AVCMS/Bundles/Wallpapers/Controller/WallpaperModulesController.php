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
     * @var \AVCMS\Bundles\Blog\Model\BlogPosts
     */
    private $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function wallpapersListModule($module, $userSettings)
    {
        $wallpapers = $this->wallpapers->find()
            ->limit($userSettings['limit'])
            ->order($userSettings['order'])
            ->published()
            ->join($this->model('WallpaperCategories'), ['id', 'name', 'slug'])
            ->get();

        return new Response($this->render('@Wallpapers/module/wallpapers_list_module.twig', array('wallpapers' => $wallpapers, 'user_settings' => $userSettings)));
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

        return new Response($this->render('@Wallpapers/module/wallpapers_list_module.twig', array('wallpapers' => $wallpapers, 'user_settings' => $userSettings)));
    }
}
