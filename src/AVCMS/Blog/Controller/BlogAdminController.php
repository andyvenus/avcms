<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Blog\Controller;

use AVCMS\Blog\Form\PostForm;
use AVCMS\Blog\Model\Post;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogAdminController extends Controller
{

    protected $parent_namespace = 'AVCMS\Blog';

    public function editPostAction(Request $request)
    {
        $posts = $this->newModel('Posts');

        if ($request->attributes->get('id')) {
            $post = $posts->getOne($request->get('id'));
        }
        else {
            $post = $posts->newEntity();
        }

        $form = $this->buildForm(new PostForm($this->getActiveUser()->getUser()->getUsername()));

        $form->addEntity($post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $form->saveToEntities();

            $posts->save($post);
        }

        return new Response($this->render('@AVBlog/edit_post.twig', array('form' => $form->createView())));
    }
}