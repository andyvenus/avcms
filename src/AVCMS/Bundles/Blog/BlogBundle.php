<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:18
 */

namespace AVCMS\Bundles\Blog;

use Assetic\Filter\ScssphpFilter;
use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class BlogBundle extends Bundle
{

    public function setUp()
    {
        $this->addTemplateDirectory('src/AVCMS/Bundles/Blog/Twig', 'AVBlog');
    }

    public function modifyContainer(ContainerBuilder $container)
    {

    }

    public function routes(RouteCollection $routes)
    {
        $routes->add('stress', new Route('/stress', array(
            '_controller' => 'AVCMS\\Games\\Controller\\GamesController::stressTestAction',
        )));

        $routes->add('blog_home', new Route('/', array(
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogController::blogHomeAction',
        )));

        $routes->add('blog_edit_post', new Route('/admin/blog/edit/{id}', array(
            'id' => null,
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::editPostAction',
            '_permissions' => array('add_edit_save', 'adverts')
        )));

        $routes->add('blog_test', new Route('/blog_test', array(
            'id' => null,
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogController::testBlogPageAction',
        )));

        $routes->add('blog_add_post', new Route('/admin/blog/new', array(
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::editPostAction',
        )));

        $routes->add('blog_finder', new Route('/admin/blog/finder/{page}', array(
            'page' => '1',
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::finderAction',
        )));

        $routes->add('blog_admin_home', new Route('/admin/blog', array(
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::blogHomeAction',
        )));

        $routes->add('blog_delete_posts', new Route('/admin/blog/delete', array(
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::deleteAction',
        )));

        $routes->add('blog_toggle_posts_published', new Route('/admin/blog/publish', array(
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::togglePublishedAction',
        )));
    }

    public function assets(AssetManager $asset_manager)
    {
        $asset_manager->addJavaScript(new BundleFileAsset('Blog', 'javascript', 'blog_js.js'));

        $asset_manager->addCSS(new BundleFileAsset('Blog', 'css', 'test.scss', array(new ScssphpFilter())));
    }

    public function bundleInfo()
    {
        return array(
            'short_name' => 'Blog',
            'full_name' => 'AVCMS Blog Bundle',
            'description' => 'A nice blog to start AVCMS off',
            'version' => '0.0.1 Alpha'
        );
    }
} 