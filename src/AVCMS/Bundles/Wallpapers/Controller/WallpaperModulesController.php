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

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function wallpapersModule($userSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->wallpapers->find()
            ->limit($userSettings['limit'])
            ->order($userSettings['order'])
            ->published();

        if ($userSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_wallpapers'), 'label' => 'All Featured Wallpapers'];
        }
        elseif ($userSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'wallpaper', $userSettings['limit']);
            $query = $this->wallpapers->find()->ids($ids, 'wallpapers.id');
            $moreButton = ['url' => $this->generateUrl('liked_games', ['likes_user' => $user->getSlug()]), 'label' => 'All Liked Wallpapers'];
        }

        if ($userSettings['show_category']) {
            $query->join($this->model('WallpaperCategories'), ['name', 'slug']);
        }

        $wallpapers = $query->get();

        if ($userSettings['layout'] === 'list') {
            $template = 'wallpapers_list_module.twig';
        }
        else {
            $template = 'wallpapers_thumbnail_module.twig';
        }

        return new Response($this->render('@Wallpapers/module/'.$template, array(
            'wallpapers' => $wallpapers,
            'user_settings' => $userSettings,
            'columns' => $userSettings['columns'],
            'more_button' => $moreButton
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
