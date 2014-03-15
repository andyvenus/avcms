<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:18
 */

namespace AVCMS\Bundles\Blog;

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

        $routes->add('edit_post', new Route('/edit_post/{id}', array(
            'id' => null,
            '_controller' => 'AVCMS\\Bundles\\Blog\\Controller\\BlogAdminController::editPostAction',
        )));
    }

    public function assets(AssetManager $asset_manager)
    {
        $asset_manager->addJavaScript(new BundleFileAsset('Blog', 'javascript', 'blog_js.js'));
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