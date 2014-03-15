<?php

namespace AVCMS\Games\Controller;

use Assetic\Filter\JSqueezeFilter;
use Assetic\FilterManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AVCMS\Core\Controller\Controller;
use AVCMS\Games\Model\Game;
use AVCMS\Core\Form\FormOutput;
use AVCMS\Games\Form\GameForm;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

class GamesController extends Controller
{

    protected $parent_namespace = 'AVCMS\Games';


    public function newModelAction(Request $request)
    {
        $games = $this->model('Games');

        $game = $games->find($request->attributes->get('id', 1))->first();

        if (!$game) {
            return new Response("Not found");
        }

        return new Response('<b>'.$game->name . '</b> - ' . $game->description);
    }

    public function blankAction()
    {
        //$games = $this->model('Games');

        //$gametwo = $this->model('Categories');

        return new Response('');
    }

    public function formAction(Request $request)
    {
        $games = $this->model('Games');

        if ($request->attributes->get('id')) {
            $game = $games->find($request->attributes->get('id'))->first();

            if (!$game) {
                return new Response("Not found");
            }
        }
        else {
            $game = new Game();
        }

        $form_game = clone $game;

        $form = $this->buildForm(new GameForm); // $form = new GameForm($game);

        $form->assignEntity($form_game);

        $form->handleRequest($request);

        // Event: Manipulate created form & entity

        if ($form->submitted()) {
            // Generate additional fields // $form_game->something = something;
            // Validator
            if ($form->isValid()) {

                $games->save($form_game); // wait, this could error...

                $game = $form_game;
            }
            else {
                foreach ($form->getErrors() as $error) {
                    echo $error['error_message'].'<br>';
                }
            }

            // Event: Game added
        }

        $print_form = new FormOutput($form);

        return new Response('<b>'.$game->name . '</b> - ' . $game->description . '<br/><br/>' . $print_form->printForm());

        /*
         *
         *      $game->file_type = GenFileType($game);
         *      $game->parent_category = $this->model('Categories')->getColumn('parent', $game->category); // always automatic
         *
         */

    }

    public function addGameAction(Request $request)
    {
        $new_game = new Game();
        $new_game->setName("Filetype Test1");
        $new_game->setUrl('http://www.andyvenus.com/file.swf');
        $games = $this->model('Games');
        $games->insert($new_game);

        return new Response("Game inserted with ID: ".$games->getInsertId());
    }

    public function joinAction(Request $request, $id)
    {
        $games = $this->model('Games');

        $categories = $this->model('Categories');

        $game = $games->find($id)->modelJoin($categories, array('name'))->first();

        return new Response( $this->render('@games/first_template.twig', array('game' => $game)) );
    }

    public function subRequestAction(Request $request)
    {
        $twig = $this->container->get('twig');

        return new Response( $twig->render('Hello {{ name }}!', array('name' => 'Andy')) ); // Response( $template->build() );
    }

    public function stressTestAction(Request $request)
    {

        $games = $this->model('Games');

        $categories = $this->model('Categories');

        $all_games = $games->query()->modelJoin($categories, array('name'))->get();

        //var_dump($all_games[0]);

        $all_games2 = $games->query()->modelJoin($categories, array('name'))->get();

        $all_games3 = $games->query()->modelJoin($categories, array('name'))->get();

        $all_games4 = $games->query()->modelJoin($categories, array('name'))->get();

        return new Response( $this->render('stress.twig', array('games' => $all_games, 'games2' => $all_games2, 'games3' => $all_games3, 'games4' => $all_games4)) ); // Response( $template->build() );
    }

    public function setUserAction($id)
    {
        /*
        $r = new Response("ITS SET");
        $r->headers->setCookie(new Cookie('avcms_userid', $id));

        return $r;

        $games = $this->model('Games');
        $categories = $this->model('Categories');

        $game = $games->find(5)
            ->select(array('name', 'description'))
            ->modelJoin($categories, array('name', 'description'))
            ->first();

        return new Response ( $game->name . ' - ' . $game->category->name . ': ' . $game->category->description );
        */

        $routes = $this->container->getParameter('routes')->all();

        foreach ($routes as $route_name => $route) {
            if (!$name = $route->getDefault('_name')) {
                $name = $route_name;
            }
            if (!$description = $route->getDefault('_description')) {
                $description = 'No description';
            }

            echo 'Name: '.$name.' Description: '.$description.'<br>';
        }

        return new Response('');
    }


    public function translateAction()
    {

        $translator = new Translator('en_GB', new MessageSelector());

        return new Response($translator->trans('Symfony Translator'));
    }

    public function testSubRequest()
    {
        // create some other request manually as needed
        $request = new Request();
        $request->attributes->add(['_controller' => 'AVCMS\\Games\\Controller\\GamesController::stressTestAction']);

        $response = $this->container->get('framework')->handle($request, HttpKernelInterface::SUB_REQUEST);

        return new Response($response->getContent());
    }

    public function testSecondary()
    {
        return new Response('Fun');
    }

    public function asseticAction()
    {
        /*
        $qb = $this->container->get('query_builder');

        $g = $qb->getQueryBuilder()->table('games')
            ->leftJoin('categories', function($table) {
                $table->on('games.category_id', '=', 'categories.id');
            })
            ->leftJoin('catjoins', function($table) {
                $table->on('categories.catjoin_id', '=', 'catjoins.id');
            })
            ->where('games.id', 1);

        return new Response($g->getQuery()->getRawSql());


        $games = $this->model('Games');
        $categories = $this->model('Categories');
        $catjoins = $this->model('CatJoins');

        $games_array = $games->query()->modelJoin($categories, array('name'))
            ->modelJoin($catjoins, array('cow'), 'left', 'category')
            ->get();

        return new Response($games_array[0]->category->catjoin->getCow());


        $games = $this->model('Games');

        $e = $games->newEntity();
        $e->setMochiId('486c1cfbc9f1e311');
        $e->setSeoUrl('sweet-heart-dressup');
        $e->setHighscores('9');

        $games->delete($e, ['mochi_id', 'seo_url']);



        $games = $this->model('Games');
        $games->query()->where('id', 1)->update(['hits' => 'games.hits + 1']);
        */


        $assetic = $this->get('assetic.factory');

        $fm = new FilterManager();
        $fm->set('minify_js', new JSqueezeFilter());

        $assetic->setFilterManager($fm);

        $css = $assetic->createAsset(array(
            '@shared_js'
        ),
        array(
            'minify_js'
        ));

        return new Response($css->dump(), 200, array('Content-Type' => 'text/javascript'));
    }
}