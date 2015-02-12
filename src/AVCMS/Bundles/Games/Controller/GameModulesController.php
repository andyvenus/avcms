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

    public function gamesModule($userSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->games->find()
            ->limit($userSettings['limit'])
            ->order($userSettings['order'])
            ->published();

        if ($userSettings['filter'] === 'featured') {
            $query->featured();
            $moreButton = ['url' => $this->generateUrl('featured_games'), 'label' => 'All Featured Games'];
        }
        elseif ($userSettings['filter'] === 'likes') {
            if (!isset($user)) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new SkipModuleException;
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'game', $userSettings['limit']);
            $query = $this->games->find()->ids($ids, 'games.id');
        }

        if ($userSettings['show_game_category']) {
            $query->join($this->model('GameCategories'), ['name', 'slug']);
        }

        $games = $query->get();

        return new Response($this->render($this->getModuleTemplate($userSettings['layout']), array(
            'games' => $games,
            'user_settings' => $userSettings,
            'columns' => $userSettings['columns'],
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

    public function tagsModule($userSettings)
    {
        return $this->getTagsModule($userSettings, 'game', 'browse_games', 'ids');
    }
}
