<?php
/**
 * User: Andy
 * Date: 18/09/2014
 * Time: 14:30
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogModulesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Blog\Model\BlogPosts
     */
    private $blogPosts;

    public function setUp()
    {
        $this->blogPosts = $this->model('BlogPosts');
    }

    public function blogPostsModule($module, $adminSettings)
    {
        $posts = $this->blogPosts->find()
            ->limit($adminSettings['limit'])
            ->order($adminSettings['order'])
            ->published()
            ->get();

        return new Response($this->render('@Blog/blog_posts_module.twig', array('posts' => $posts, 'admin_settings' => $adminSettings)));
    }
}
