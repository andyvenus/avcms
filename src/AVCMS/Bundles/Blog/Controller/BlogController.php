<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Finder\BlogPostsFinder;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class BlogController extends Controller
{
    public function blogArchiveAction(Request $request)
    {
        $posts = $this->model($this->bundle->model->posts);

        $finder = $posts->find();
        $all_posts = $finder->published()
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'tags' => null))
            ->join($this->model($this->bundle->model->users), ['username'])
            ->get();

        return new Response($this->render($this->bundle->template->blog_home, array('posts' => $all_posts)));
    }

    public function blogPostAction(Request $request)
    {
        $posts = $this->model($this->bundle->model->posts);

        $post = $posts->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model($this->bundle->model->users), ['username'])
            ->first();

        if (!$post) {
            throw $this->createNotFoundException(ucfirst($posts->getSingular()).' not found');
        }

        $this->container->get('taxonomy_manager')->assign('tags', $post, $posts->getSingular());

        return new Response($this->render($this->bundle->template->blog_post, array('post' => $post)));
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
        return new Response($this->render('@Blog/test2.twig'));
    }
}