<?php
/**
 * User: Andy
 * Date: 12/02/15
 * Time: 14:09
 */

namespace AVCMS\Bundles\Images\Controller;

use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\Response;

class ImageModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Images\Model\Images
     */
    private $images;

    public function setUp()
    {
        $this->images = $this->model('Images');
    }

    public function imagesModule($adminSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->images->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

        if ($adminSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_images'), 'label' => 'All Featured Images'];
        }
        elseif ($adminSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'image', $adminSettings['limit']);
            $query = $this->images->find()->ids($ids, 'images.id');
            $moreButton = ['url' => $this->generateUrl('liked_images', ['likes_user' => $user->getSlug()]), 'label' => 'All Liked Images'];
        }

        if ($adminSettings['show_image_category']) {
            $query->join($this->model('ImageCategories'), ['name', 'slug']);
        }

        $images = $query->get();

        return new Response($this->render($this->getModuleTemplate($adminSettings['layout']), array(
            'images' => $images,
            'admin_settings' => $adminSettings,
            'columns' => $adminSettings['columns'],
            'more_button' => $moreButton
        )));
    }

    protected function getModuleTemplate($layout)
    {
        if ($layout === 'list') {
            $template = 'images_list_module.twig';
        }
        else {
            $template = 'images_thumbnail_module.twig';
        }

        return '@Images/module/'.$template;
    }

    public function tagsModule($adminSettings)
    {
        return $this->getTagsModule($adminSettings, 'image', 'browse_images', 'tags');
    }

    public function imageStatsModule()
    {
        $totalImages = $this->images->query()->count();
        $totalPlays = $this->images->query()->select([$this->images->query()->raw('SUM(hits) as total_plays')])->first(\PDO::FETCH_ASSOC)['total_plays'];
        $totalLikes = $this->images->query()->select([$this->images->query()->raw('SUM(likes) as total_likes')])->first(\PDO::FETCH_ASSOC)['total_likes'];

        return new Response($this->render('@Images/module/image_stats_module.twig', [
            'total_images' => $totalImages,
            'total_plays' => $totalPlays,
            'total_likes' => $totalLikes
        ]));
    }
}
