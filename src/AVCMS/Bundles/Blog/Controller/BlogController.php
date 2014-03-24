<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller {

    protected $parent_namespace = 'AVCMS\Bundles\Blog';

    public function blogHomeAction()
    {
        $posts = $this->model('Posts');

        $all_posts = $posts->query()->get();

        $user = $this->getActiveUser();

        return new Response($this->render('@AVBlog/blog_home.twig', array('posts' => $all_posts, 'user' => $user->getUser())));
    }

    public function blogLatestModule()
    {
        $posts = $this->model('Posts');

        $all_posts = $posts->query()->get();

        $user = $this->getActiveUser();

        return new Response($this->render('blog_top_module.twig', array('posts' => $all_posts, 'user' => $user->getUser())));
    }

    public function testBlogPageAction()
    {
        return new Response($this->render('@AVBlog/test.twig'));
    }
}