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

    private $blogCategories;

    public function setUp()
    {
        $this->posts = $this->model('BlogPosts');
        $this->blogCategories = $this->model('BlogCategories');
    }

    public function blogArchiveAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->posts->find();
        $finder->published()
            ->setResultsPerPage($this->setting('blog_posts_per_page'))
            ->setSearchFields(['title'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'tags' => null, 'search' => null))
            ->join($this->model('Users'), ['id', 'username', 'slug', 'avatar'])
            ->join($this->model('BlogCategories'), ['name', 'slug']);

        $category = null;
        if ($request->get('category') !== null) {
            $category = $this->blogCategories->getFullCategory($request->get('category'));

            if ($category) {
                $finder->category($category);
            }
            else {
                throw $this->createNotFoundException('Category Not Found');
            }
        }

        $allPosts = $finder->get();

        return new Response($this->render('@Blog/blog_archive.twig', array(
            'posts' => $allPosts,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'finder_request' => $finder->getRequestFilters(),
        )));
    }

    public function blogPostAction(Request $request)
    {
        $post = $this->posts->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model('Users'), ['id', 'username', 'slug', 'avatar'])
            ->join($this->model('BlogCategories'), ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$post) {
            throw $this->createNotFoundException('Blog Post Not Found');
        }

        $this->container->get('hitcounter')->registerHit($this->posts, $post->getId());

        return new Response($this->render('@Blog/blog_post_page.twig', array('post' => $post)));
    }
}
