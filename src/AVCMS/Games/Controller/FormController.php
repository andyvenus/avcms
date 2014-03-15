<?php
/**
 * User: Andy
 * Date: 17/01/2014
 * Time: 15:22
 */

namespace AVCMS\Games\Controller;

use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Games\Form\TestForm;
use AVCMS\Games\Model\Game;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller {
    protected $parent_namespace = 'AVCMS\Games';

    public function formTestAction(Request $request, $id)
    {
        $form = $this->buildForm(new TestForm());

        $games = $this->model('Games');
        $game = $games->find($id)->first();

        $form->bindEntity($game);

        /*
         * $handler->setMethod('GET');
         * $handler->setAction('site.com/somethingorother');
         */

        $form->handleRequest($request, 'symfony');

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $games->save($game);
            }
        }

        return new Response($this->render('test_form.twig', array('form' => $form->createView())));
    }

    public function formArrayTestAction(Request $request, $id)
    {
        $form_bp = new FormBlueprint();
       $form_bp->add('grabbaa', 'text', array(
            'default' => 'Nips',
            'label' => 'Flump'
        ));
        $form_bp->add('thing[one]', 'text', array(
            'default' => 'ROFLCOPTER',
            'label' => 'Hurk'
        ));
        $form_bp->add('thing[two]', 'text', array(
            'default' => 'lawl'
        ));
        $form_bp->add('thing[three]', 'text');

        $form_bp->add('thing[bish][bosh]', 'text', array(
            'default' => 'BISH BOSH'
        ));

        $form_bp->add('thing[bish][bash]', 'text', array(
            'default' => 'BISH BASH'
        ));

        $form_bp->add('fred[]', 'text', array(
            'default' => 'somethinghere'
        ));

        $form_bp->add('fred[]', 'text');

        $form_bp->add('fred[]', 'text', array(
            'label' => 'Submit'
        ));

        //var_dump($form_bp->getAll());

        $form = $this->buildForm($form_bp);

        $form->handleRequest($request, 'symfony');

        if ($form->isSubmitted()) {
            echo 'submitted';
        }

        //var_dump($form->getFields());

        $form_view = $form->createView();

        return new Response($this->render('test_form.twig', array('form' => $form_view)));
    }

    public function validatorTestAction()
    {
        $validator = $this->newValidator();

        $game = new Game();
        $game->setName('Turn B');
        $game->setDescription('Yo turn my base battle KKK');

        $validator->validate($game);

        if (!$validator->isValid()) {
            echo 'There were errors:<br>';

            foreach ($validator->getErrors() as $error) {
                echo $error['error_message'].'<br>';
            }
        }

        return new Response('');
    }

    public function fileUploadAction(Request $request)
    {
        $form = new FormBlueprint();
        $form->add('file', 'file', array(
            'label' => 'A file here'
        ));

        $form = $this->buildForm($form);

        $form->handleRequest($request, 'symfony');


        if ($form->isSubmitted()) {
            echo 'Form submitted';

            $the_file = $form->getData('file');

            $the_file->move('uploads', $the_file->getClientOriginalName().'.'.$the_file->getClientOriginalExtension());
        }

        return new Response($this->render('test_form.twig', array('form' => $form->createView())));
    }
}