<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            ->setSearchFields(['title'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'tags' => null, 'search' => null))
            ->join($this->model($this->bundle->model->users), ['id', 'username', 'slug', 'avatar'])
            ->get();

        return new Response($this->render('@Blog/blog_home.twig', array('posts' => $allPosts, 'total_pages' => $finder->getTotalPages(), 'current_page' => $finder->getCurrentPage())));
    }

    public function blogPostAction(Request $request)
    {
        $post = $this->posts->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model($this->bundle->model->users), ['id', 'username', 'slug', 'avatar'])
            ->first();

        if (!$post) {
            throw $this->createNotFoundException(ucfirst($this->posts->getSingular()).' not found');
        }

        $this->container->get('taxonomy_manager')->assign('tags', $post, $this->posts->getSingular());

        $this->container->get('hitcounter')->registerHit($this->posts, $post->getId());

        return new Response($this->render('@Blog/blog_post.twig', array('post' => $post)));
    }
}