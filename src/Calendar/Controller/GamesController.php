<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AVCMS\Controller\Controller;
use Calendar\Model\Game;
use AVCMS\Form\FormOutput;
use Calendar\Form\GameForm;

class GamesController extends Controller
{

    protected $parent_namespace = "Calendar";


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
        $games = $this->newModel('Games');

        $categories = $this->newModel('Categories');

        $games->setJoin($categories, array('name'));

        $game = $games->find($id)->first();

        return new Response($game->name.' Category: '.$game->category->name);
    }
}