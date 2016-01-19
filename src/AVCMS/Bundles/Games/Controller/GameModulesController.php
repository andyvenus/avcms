<?php
/**
 * User: Andy
 * Date: 12/02/15
 * Time: 14:09
 */

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Bundles\Users\Model\User;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Module\Exception\SkipModuleException;
use Symfony\Component\HttpFoundation\Response;

class GameModulesController extends Controller
{
    use TagsModuleTrait;

    /**
     * @var \AVCMS\Bundles\Games\Model\Games
     */
    private $games;

    /**
     * @var \AVCMS\Bundles\Games\Model\GameCategories
     */
    private $gameCategories;

    public function setUp()
    {
        $this->games = $this->model('Games');
        $this->gameCategories = $this->model('GameCategories');
    }

    public function gamesModule($adminSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->games->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

        if ($adminSettings['category']) {
            $category = $this->gameCategories->getOne($adminSettings['category']);

            if ($category) {
                $query->category($category);
            }
        }

        if ($adminSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_games'), 'label' => 'All Featured Games'];
        }
        elseif ($adminSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'game');
            $query->ids($ids, 'games.id');
            $moreButton = ['url' => $this->generateUrl('liked_games', ['likes_user' => $user->getSlug()]), 'label' => 'All Liked Games'];
        }
        else {
            if ($adminSettings['more_button_start_page'] == 2) {
                $pageTwoExists = ($query->getQuery()->count() > $this->setting('browse_games_per_page'));
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
                $moreButtonRoute = 'game_category';
                $moreButtonAttr['category'] = $category->getSlug();
            } else {
                $moreButtonRoute = 'browse_games';
            }

            $moreButton = ['url' => $this->generateUrl($moreButtonRoute, $moreButtonAttr), 'label' => $label];
        }

        if ($adminSettings['show_game_category']) {
            $query->join($this->model('GameCategories'), ['name', 'slug']);
        }

        $games = $query->get();

        return new Response($this->render($this->getModuleTemplate($adminSettings['layout']), array(
            'games' => $games,
            'admin_settings' => $adminSettings,
            'columns' => $adminSettings['columns'],
            'more_button' => $moreButton
        )));
    }

    protected function getModuleTemplate($layout)
    {
        if ($layout === 'list') {
            $template = 'games_list_module.twig';
        }
        else {
            $template = 'games_thumbnail_module.twig';
        }

        return '@Games/module/'.$template;
    }

    public function tagsModule($adminSettings)
    {
        return $this->getTagsModule($adminSettings, 'game', 'game_tag', 'tags');
    }

    public function gameStatsModule()
    {
        $totalGames = $this->games->query()->count();
        $totalPlays = $this->games->query()->select([$this->games->query()->raw('SUM(hits) as total_plays')])->first(\PDO::FETCH_ASSOC)['total_plays'];
        $totalLikes = $this->games->query()->select([$this->games->query()->raw('SUM(likes) as total_likes')])->first(\PDO::FETCH_ASSOC)['total_likes'];

        return new Response($this->render('@Games/module/game_stats_module.twig', [
            'total_games' => $totalGames,
            'total_plays' => $totalPlays,
            'total_likes' => $totalLikes
        ]));
    }
}
