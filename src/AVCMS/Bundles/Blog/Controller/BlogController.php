<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Finder\BlogPostsFinder;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller {

    protected $parent_namespace = 'AVCMS\Bundles\Blog';

    public function blogHomeAction()
    {
        $posts = $this->model('Posts');

        $all_posts = $posts->query()->get();

        $user = $this->activeUser();

        return new Response($this->render('@AVBlog/blog_home.twig', array('posts' => $all_posts, 'user' => $user->getUser())));
    }

    public function blogLatestModule()
    {
        $posts = $this->model('Posts');

        $all_posts = $posts->query()->get();

        $user = $this->activeUser();

        return new Response($this->render('blog_top_module.twig', array('posts' => $all_posts, 'user' => $user->getUser())));
    }

    public function testBlogPageAction(Request $request)
    {
        /*
        $posts = new BlogPostsFinder($this->model('Posts'), $this->container->get('taxonomy.manager'));

        $posts->handleRequest($request, array('tags' => 'crapp'));

        $x = $posts->getQuery()->get();

        var_dump($x);
        */

        $posts = $this->model('Posts');
        $mypost = $posts->getOne(1);

        $tax = $this->container->get('taxonomy.manager');
        $tax->update('tags', 1, 'blog_post', ['one', 'two', 'three', 'four']);

        return new Response($this->render('@AVBlog/test.twig'));
    }
}