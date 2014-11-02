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
    /**
     * @var \AVCMS\Bundles\Blog\Model\BlogPosts
     */
    private $posts;

    public function setUp()
    {
        $this->posts = $this->model('BlogPosts');
    }

    public function blogArchiveAction(Request $request)
    {
        $finder = $this->posts->find();
        $allPosts = $finder->published()
            ->setResultsPerPage(10)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'tags' => null))
            ->join($this->model($this->bundle->model->users), ['username'])
            ->get();

        return new Response($this->render('@Blog/blog_home.twig', array('posts' => $allPosts, 'total_pages' => $finder->getTotalPages(), 'current_page' => $finder->getCurrentPage())));
    }

    public function blogPostAction(Request $request)
    {
        $post = $this->posts->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model($this->bundle->model->users), ['username'])
            ->first();

        if (!$post) {
            throw $this->createNotFoundException(ucfirst($this->posts->getSingular()).' not found');
        }

        $this->container->get('taxonomy_manager')->assign('tags', $post, $this->posts->getSingular());

        return new Response($this->render('@Blog/blog_post.twig', array('post' => $post)));
    }
}