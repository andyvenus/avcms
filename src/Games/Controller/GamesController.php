<?php

namespace Games\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AVCMS\Controller\Controller;
use Games\Model\Game;
use AVCMS\Form\FormOutput;
use Games\Form\GameForm;

class GamesController extends Controller
{

    protected $parent_namespace = "Games";


    public function newModelAction(Request $request)
    {
        $games = $this->newModel('Games');

        $game = $games->find($request->attributes->get('id', 1));

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

        echo $this->getUser()->name;

        $games = $this->newModel('Games');

        $categories = $this->newModel('Categories');

        $game = $games->find($id)->modelJoin($categories, array('name'))->first();

        $twig = $this->container->get('twig');

        return new Response( $twig->render('{{ game.name }}, {{ game.category.name }}', array('game' => $game)) );
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

        $games->setJoin($categories, array('name'));

        $all_games = $games->select()->get();

        $twig = $this->container->get('twig');

        return new Response( $twig->render('{% for game in games %} {{ game.name }} in cat {{ game.category.name }} <br /> {% endfor %}', array('games' => $all_games)) ); // Response( $template->build() );
    }

    public function setUserAction($id)
    {
        /*
        $r = new Response("ITS SET");
        $r->headers->setCookie(new Cookie('avcms_userid', $id));

        return $r; */

        $games = $this->newModel('Games');
        $categories = $this->newModel('Categories');

        $game = $games->find(5)
            ->select(array('name', 'description'))
            ->modelJoin($categories, array('name', 'description'))
            ->first();

        return new Response ( $game->name . ' - ' . $game->category->name . ': ' . $game->category->description );
    }
}