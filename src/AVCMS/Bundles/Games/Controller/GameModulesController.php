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

    public function gamesModule($adminSettings, User $user = null)
    {
        $moreButton = null;

        $query = $this->games->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published();

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
            $ids = $ratings->getLikedIds($user->getId(), 'game', $adminSettings['limit']);
            $query = $this->games->find()->ids($ids, 'games.id');
            $moreButton = ['url' => $this->generateUrl('liked_games', ['likes_user' => $user->getSlug()]), 'label' => 'All Liked Games'];
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
        return $this->getTagsModule($adminSettings, 'game', 'browse_games', 'tags');
    }
}
