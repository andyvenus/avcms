<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 15:06
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

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

        return new Response($this->render('@Wallpapers/wallpapers_list_module.twig', array('wallpapers' => $wallpapers, 'user_settings' => $userSettings)));
    }
}
