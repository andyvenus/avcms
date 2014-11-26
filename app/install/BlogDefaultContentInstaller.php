<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class BlogDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults']
        );
    }

    public function cmsDefaults()
    {
        // Menu Items
        $menuItems = $this->modelFactory->create('AVCMS\Bundles\CmsFoundation\Model\MenuItems');

        $homeItem = $menuItems->newEntity();
        $homeItem->setId('main_menu_home');
        $homeItem->setMenu('frontend');
        $homeItem->setType('route');
        $homeItem->setLabel('Home');
        $homeItem->setTarget('home');
        $homeItem->setIcon('glyphicon glyphicon-home');

        $menuItems->save($homeItem);

        // Modules
        $modules = $this->modelFactory->create('AVCMS\Bundles\CmsFoundation\Model\Modules');

        // Newest Blog Posts - Left Sidebar
        $newestPostsModule = $modules->newEntity();
        $newestPostsModule->setModule('blog_posts');
        $newestPostsModule->setActive(1);
        $newestPostsModule->setPosition('left_sidebar');
        $newestPostsModule->setTitle('Latest Posts');
        $newestPostsModule->setShowHeader(1);
        $newestPostsModule->setTemplateType('list_panel');

        $modules->save($newestPostsModule);

        // Reports - Admin Dashboard
        $reportsModule = $modules->newEntity();
        $reportsModule->setModule('reports');
        $reportsModule->setActive(1);
        $reportsModule->setPosition('admin_dashboard');
        $reportsModule->setTitle('Reports');
        $reportsModule->setShowHeader(1);
        $reportsModule->setTemplateType('panel');

        $modules->save($reportsModule);
    }

    public function blogDefaults()
    {
        $blogPosts = $this->modelFactory->create('AVCMS\Bundles\Blog\Model\BlogPosts');

        $post = $blogPosts->newEntity();
        $post->setTitle('Welcome to AVCMS');
        $post->setBody('<p>Welcome to AVCMS, a new modular content management system for 2015.</p>
            <p>This first version is just a simple blog to test out the system and get it running well for the upcoming major releases from AV Scripts. Check out the custom menus and modules, or the powerful new admin panel and please send any feedback you have.</p>
            <p>Andy</p>');
        $post->setSlug('welcome-to-avcms');
        $post->setPublishDate(time());

        $blogPosts->save($post);
    }
}