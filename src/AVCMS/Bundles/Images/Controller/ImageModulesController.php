<?php
/**
 * User: Andy
 * Date: 12/02/15
 * Time: 14:09
 */

namespace AVCMS\Bundles\Images\Controller;

use AVCMS\Bundles\Images\Model\ImageCollection;
use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\Response;

class ImageModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageCollections
     */
    private $images;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageCategories
     */
    private $imageCategories;

    public function setUp()
    {
        $this->images = $this->model('ImageCollections');
        $this->imageCategories = $this->model('ImageCategories');
    }

    public function imagesModule($adminSettings, User $user = null, ImageCollection $imageCollection = null)
    {
        $moreButton = null;

        $query = $this->images->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

        if ($adminSettings['category']) {
            $category = $this->imageCategories->getOne($adminSettings['category']);

            if ($category) {
                $query->category($category);
            }
        }

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
            $ids = $ratings->getLikedIds($user->getId(), 'image');
            $query->ids($ids, 'image_collections.id');
            $moreButton = ['url' => $this->generateUrl('liked_images', ['filter_user' => $user->getSlug()]), 'label' => 'All Liked Images'];
        }
        elseif ($adminSettings['filter'] === 'submitted') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $query->author($user->getId());
            $moreButton = ['url' => $this->generateUrl('submitted_images', ['filter_user' => $user->getSlug()]), 'label' => 'All Submitted Images'];
        }
        elseif ($adminSettings['filter'] == 'related') {
            if ($imageCollection && isset($imageCollection->category)) {
                $query->category($imageCollection->category)->getQuery()->where('image_collections.id', '!=', $imageCollection->getId());
                $moreButton = null;
            }
        }
        else {
            if ($adminSettings['more_button_start_page'] == 2) {
                $pageTwoExists = ($query->getQuery()->count() > $this->setting('browse_images_per_page'));
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
                $moreButtonRoute = 'image_category';
                $moreButtonAttr['category'] = $category->getSlug();
            } else {
                $moreButtonRoute = 'browse_images';
            }

            $moreButton = ['url' => $this->generateUrl($moreButtonRoute, $moreButtonAttr), 'label' => $label];
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
        return $this->getTagsModule($adminSettings, 'image_collection', 'browse_images', 'tags');
    }

    public function imageStatsModule()
    {
        $totalImages = $this->images->query()->count();
        $totalHits = $this->images->query()->select([$this->images->query()->raw('SUM(hits) as total_hits')])->first(\PDO::FETCH_ASSOC)['total_hits'];
        $totalLikes = $this->images->query()->select([$this->images->query()->raw('SUM(likes) as total_likes')])->first(\PDO::FETCH_ASSOC)['total_likes'];

        return new Response($this->render('@Images/module/image_stats_module.twig', [
            'total_images' => $totalImages,
            'total_hits' => $totalHits,
            'total_likes' => $totalLikes
        ]));
    }
}
