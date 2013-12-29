<?php

namespace AVCMS\Games\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AVCMS\Core\Controller\Controller;
use AVCMS\Games\Model\Game;
use AVCMS\Core\Form\FormOutput;
use AVCMS\Games\Form\GameForm;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

class GamesController extends Controller
{

    protected $parent_namespace = 'AVCMS\Games';


    public function newModelAction(Request $request)
    {
        $games = $this->newModel('Games');

        $game = $games->find($request->attributes->get('id', 1))->first();

        if (!$game) {
            return new Response("Not found");
        }

        return new Response('<b>'.$game->name . '</b> - ' . $game->description);
    }

    public function blankAction()
    {
        //$games = $this->newModel('Games');

        //$gametwo = $this->newModel('Categories');

        return new Response('');
    }

    public function formAction(Request $request)
    {
        $games = $this->newModel('Games');

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
        $new_game->name = "Filetype Test1";
        $new_game->url = 'http://www.andyvenus.com/file.swf';
        $games = $this->newModel('Games');
        $games->insert($new_game);

        return new Response("Game inserted with ID: ".$games->getInsertId());
    }

    public function joinAction(Request $request, $id)
    {
        $games = $this->newModel('Games');

        $categories = $this->newModel('Categories');

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

        $games = $this->newModel('Games');

        $categories = $this->newModel('Categories');

        $all_games = $games->query()->modelJoin($categories, array('name'))->get();

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

        $games = $this->newModel('Games');
        $categories = $this->newModel('Categories');

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
}