<?php
/**
 * User: Andy
 * Date: 12/02/15
 * Time: 14:09
 */

namespace AVCMS\Bundles\Videos\Controller;

use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Bundles\Videos\Model\Video;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\Response;

class VideoModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Videos\Model\Videos
     */
    private $videos;

    /**
     * @var \AVCMS\Bundles\Videos\Model\VideoCategories
     */
    private $videoCategories;

    public function setUp()
    {
        $this->videos = $this->model('Videos');
        $this->videoCategories = $this->model('VideoCategories');
    }

    public function videosModule($adminSettings, User $user = null, Video $video = null)
    {
        $moreButton = null;

        $query = $this->videos->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

        if ($adminSettings['category']) {
            $category = $this->videoCategories->getOne($adminSettings['category']);

            if ($category) {
                $query->category($category);
            }
        }

        if ($adminSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_videos'), 'label' => 'All Featured Videos'];
        }
        elseif ($adminSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'video');
            $query->ids($ids, 'videos.id');
            $moreButton = ['url' => $this->generateUrl('liked_videos', ['filter_user' => $user->getSlug()]), 'label' => 'All Liked Videos'];
        }
        elseif ($adminSettings['filter'] === 'submitted') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $query->author($user->getId());
            $moreButton = ['url' => $this->generateUrl('submitted_videos', ['filter_user' => $user->getSlug()]), 'label' => 'All Submitted Videos'];
        }
        elseif ($adminSettings['filter'] == 'related') {
            if ($video && isset($video->category)) {
                $query->category($video->category)->getQuery()->where('videos.id', '!=', $video->getId());
                $moreButton = null;
            }
        }
        else {
            if ($adminSettings['more_button_start_page'] == 2) {
                $pageTwoExists = ($query->getQuery()->count() > $this->setting('browse_videos_per_page'));
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
                $moreButtonRoute = 'video_category';
                $moreButtonAttr['category'] = $category->getSlug();
            } else {
                $moreButtonRoute = 'browse_videos';
            }

            $moreButton = ['url' => $this->generateUrl($moreButtonRoute, $moreButtonAttr), 'label' => $label];
        }

        if ($adminSettings['show_video_category']) {
            $query->join($this->model('VideoCategories'), ['name', 'slug']);
        }

        $videos = $query->get();

        return new Response($this->render($this->getModuleTemplate($adminSettings['layout']), array(
            'videos' => $videos,
            'admin_settings' => $adminSettings,
            'columns' => $adminSettings['columns'],
            'more_button' => $moreButton
        )));
    }

    protected function getModuleTemplate($layout)
    {
        if ($layout === 'list') {
            $template = 'videos_list_module.twig';
        }
        else {
            $template = 'videos_thumbnail_module.twig';
        }

        return '@Videos/module/'.$template;
    }

    public function tagsModule($adminSettings)
    {
        return $this->getTagsModule($adminSettings, 'video', 'browse_videos', 'tags');
    }

    public function videoStatsModule()
    {
        $totalVideos = $this->videos->query()->count();
        $totalPlays = $this->videos->query()->select([$this->videos->query()->raw('SUM(hits) as total_plays')])->first(\PDO::FETCH_ASSOC)['total_plays'];
        $totalLikes = $this->videos->query()->select([$this->videos->query()->raw('SUM(likes) as total_likes')])->first(\PDO::FETCH_ASSOC)['total_likes'];

        return new Response($this->render('@Videos/module/video_stats_module.twig', [
            'total_videos' => $totalVideos,
            'total_plays' => $totalPlays,
            'total_likes' => $totalLikes
        ]));
    }
}
