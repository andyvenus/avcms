<?php
/**
 * User: Andy
 * Date: 18/09/2014
 * Time: 14:30
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Tags\Module\TagsModuleTrait;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogModulesController extends Controller
{
    use TagsModuleTrait;

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

    public function tagsModule($adminSettings)
    {
        return $this->getTagsModule($adminSettings, 'blog_post', 'blog_archive', 'tags');
    }
}
