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

    public function setUp()
    {
        $this->games = $this->model('Games');
    }

    public function gamesListModule($userSettings)
    {
        $moreButton = null;

        $query = $this->games->find()
            ->limit($userSettings['limit'])
            ->order($userSettings['order'])
            ->published();

        if ($userSettings['show_game_category']) {
            $query->join($this->model('GameCategories'), ['id', 'name', 'slug']);
        }

        if ($userSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_games'), 'label' => 'All Featured Games'];
        }

        $games = $query->get();

        return new Response($this->render($this->getModuleTemplate($userSettings['layout']), array(
            'games' => $games,
            'user_settings' => $userSettings,
            'columns' => $userSettings['columns'],
            'more_button' => $moreButton
        )));
    }

    public function likedGamesModule($userSettings, User $user = null)
    {
        if (!isset($user)) {
            $user = $this->activeUser();

            if (!$user->getId()) {
                throw new SkipModuleException;
            }
        }

        $ratings = $this->model('LikeDislike:Ratings');

        $liked = $ratings->query()
            ->where('user_id', $user->getId())
            ->where('content_type', 'game')
            ->where('rating', 1)
            ->select(['content_id'])
            ->limit($userSettings['limit'])
            ->get();

        $ids = [];
        foreach ($liked as $like) {
            $ids[] = $like->getContentId();
        }

        if (!empty($ids)) {
            $query = $this->games->query()->whereIn('games.id', $ids);

            if ($userSettings['show_game_category']) {
                $query->modelJoin($this->model('GameCategories'), ['name', 'slug']);
            }

            $games = $query->get();
        }
        else {
            $games = [];
        }

        return new Response($this->render($this->getModuleTemplate($userSettings['layout']), array(
            'games' => $games,
            'user_settings' => $userSettings,
            'columns' => $userSettings['columns'],
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

    public function tagsModule($userSettings)
    {
        return $this->getTagsModule($userSettings, 'game', 'browse_games', 'ids');
    }
}
