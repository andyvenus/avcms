<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Form\PostForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogAdminController extends Controller
{

    protected $parent_namespace = 'AVCMS\Bundles\Blog';

    public function editPostAction(Request $request)
    {
        $posts = $this->model('Posts');

        $post = $posts->getOneOrNew($request->get('id', 0));

        $form = $this->buildForm(new PostForm($this->getActiveUser()->getUser()->getUsername()));

        $form->bindEntity($post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $posts->save($post);
        }

        return new Response($this->render('@AVBlog/edit_post.twig', array('form' => $form->createView())));
    }
}