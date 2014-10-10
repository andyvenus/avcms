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
     * @var \AVCMS\Bundles\Blog\Model\Posts
     */
    private $blogPosts;

    public function setUp()
    {
        $this->blogPosts = $this->model('Posts');
    }

    public function blogPostsModule($module, $userSettings)
    {
        $posts = $this->blogPosts->find()->limit($userSettings['limit'])->get();

        return new Response($this->render('@Blog/blog_posts_module.twig', array('posts' => $posts, 'user_settings' => $userSettings)));
    }
}