<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssFeed;
use AVCMS\Core\Rss\RssItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            ->handleRequest($request, array('page' => 1, 'order' => 'publish_date_newest', 'tags' => null, 'search' => null))
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

    // todo: test
    public function blogRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Blog\Model\BlogPost[] $posts
         */
        $posts = $this->posts->find()->published()->limit(30)->order('publish-date-newest')->get();

        $feed = new RssFeed(
            $this->trans('Blog Posts'),
            $this->generateUrl('blog_archive', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->trans('The latest blog posts')
        );

        foreach ($posts as $post) {
            $url = $this->generateUrl('blog_post', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            $date = new \DateTime();
            $date->setTimestamp($post->getPublishDate());

            $feed->addItem(new RssItem($post->getTitle(), $url, $date, strip_tags($post->getBody())));
        }

        return new Response($feed->build(), 200, ['Content-Type' => 'application/xml']);
    }
}
