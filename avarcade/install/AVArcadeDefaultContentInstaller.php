<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class AVArcadeDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults'],
            'Games' => ['1.0' => 'gameDefaults']
        );
    }

    public function cmsDefaults()
    {
        // Menu Items
        $menuItems = $this->modelFactory->create('AVCMS\Bundles\CmsFoundation\Model\MenuItems');

        $homeItem = $menuItems->newEntity();
        $homeItem->setMenu('frontend');
        $homeItem->setType('route');
        $homeItem->setLabel('Home');
        $homeItem->setSettings(['route' => 'home']);
        $homeItem->setIcon('glyphicon glyphicon-home');
        $homeItem->setOrder(1);

        $contactItem = $menuItems->newEntity();
        $contactItem->setMenu('frontend_footer');
        $contactItem->setType('route');
        $contactItem->setLabel('Contact Us');
        $contactItem->setSettings(['route' => 'contact_us']);
        $contactItem->setIcon('glyphicon glyphicon-envelope');

        $linksItem = $menuItems->newEntity();
        $linksItem->setMenu('frontend_footer');
        $linksItem->setType('route');
        $linksItem->setLabel('Link Exchange');
        $linksItem->setSettings(['route' => 'links']);
        $linksItem->setIcon('glyphicon glyphicon-link');

        $menuItems->insert([$homeItem, $contactItem, $linksItem]);

        // Modules
        $modules = $this->modelFactory->create('AVCMS\Bundles\CmsFoundation\Model\Modules');

        // Newest Games - Sidebar
        $newestGamesSidebar = $modules->newEntity();
        $newestGamesSidebar->setModule('games');
        $newestGamesSidebar->setActive(1);
        $newestGamesSidebar->setPosition('sidebar');
        $newestGamesSidebar->setTitle('Latest Games');
        $newestGamesSidebar->setShowHeader(1);
        $newestGamesSidebar->setTemplateType('list_panel');
        $newestGamesSidebar->setCacheTime(3600);

        // Featured Games - Homepage
        $featuredGamesHome = $modules->newEntity();
        $featuredGamesHome->setModule('games');
        $featuredGamesHome->setActive(1);
        $featuredGamesHome->setPosition('homepage');
        $featuredGamesHome->setTitle('Featured Games');
        $featuredGamesHome->setShowHeader(1);
        $featuredGamesHome->setTemplateType('content');
        $featuredGamesHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 4, 'limit' => 8]);
        $featuredGamesHome->setCacheTime(3600);

        // Newest Games - Homepage
        $newestGamesHome = $modules->newEntity();
        $newestGamesHome->setModule('games');
        $newestGamesHome->setActive(1);
        $newestGamesHome->setPosition('homepage');
        $newestGamesHome->setTitle('Newest Games');
        $newestGamesHome->setShowHeader(1);
        $newestGamesHome->setTemplateType('content');
        $newestGamesHome->setSettingsArray(['layout' => 'details', 'columns' => 2, 'limit' => 18]);
        $newestGamesHome->setCacheTime(3600);

        // Newest Blog Posts - Sidebar
        $newestPostsModule = $modules->newEntity();
        $newestPostsModule->setModule('blog_posts');
        $newestPostsModule->setActive(1);
        $newestPostsModule->setPosition('sidebar');
        $newestPostsModule->setTitle('Latest Blog Posts');
        $newestPostsModule->setShowHeader(1);
        $newestPostsModule->setTemplateType('list_panel');
        $newestPostsModule->setCacheTime(3600);

        // Game Tags - Sidebar
        $gameTags = $modules->newEntity();
        $gameTags->setModule('game_tags');
        $gameTags->setActive(1);
        $gameTags->setPosition('sidebar');
        $gameTags->setTitle('Game Tags');
        $gameTags->setShowHeader(1);
        $gameTags->setTemplateType('panel');
        $gameTags->setCacheTime(43200);

        // Links - Sidebar
        $linksModule = $modules->newEntity();
        $linksModule->setModule('links');
        $linksModule->setActive(1);
        $linksModule->setPosition('sidebar');
        $linksModule->setTitle('Links');
        $linksModule->setShowHeader(1);
        $linksModule->setTemplateType('list_panel');
        $linksModule->setCacheTime(43200);

        // Share - Sidebar
        $shareModule = $modules->newEntity();
        $shareModule->setModule('share_module');
        $shareModule->setActive(1);
        $shareModule->setPosition('sidebar');
        $shareModule->setTitle('Share');
        $shareModule->setShowHeader(1);
        $shareModule->setTemplateType('panel');
        $shareModule->setCacheTime(43200);

        $modules->insert([$newestGamesSidebar, $featuredGamesHome, $newestGamesHome, $newestPostsModule, $gameTags, $linksModule, $shareModule]);

        // Updates - Admin Dashboard
        $updatesModule = $modules->newEntity();
        $updatesModule->setModule('avcms_updates');
        $updatesModule->setActive(1);
        $updatesModule->setPosition('admin_dashboard');
        $updatesModule->setTitle('Updates');
        $updatesModule->setShowHeader(1);
        $updatesModule->setTemplateType('panel');
        $updatesModule->setCacheTime(999999);

        // Reports - Admin Dashboard
        $reportsModule = $modules->newEntity();
        $reportsModule->setModule('reports');
        $reportsModule->setActive(1);
        $reportsModule->setPosition('admin_dashboard');
        $reportsModule->setTitle('Reports');
        $reportsModule->setShowHeader(1);
        $reportsModule->setTemplateType('panel');
        $reportsModule->setPermissions('ADMIN_REPORTS');
        $reportsModule->setCacheTime(3600);

        // AVS News - Admin Dashboard
        $avsNewsModule = $modules->newEntity();
        $avsNewsModule->setModule('avcms_news');
        $avsNewsModule->setActive(1);
        $avsNewsModule->setPosition('admin_dashboard');
        $avsNewsModule->setTitle('AV Scripts News');
        $avsNewsModule->setShowHeader(1);
        $avsNewsModule->setTemplateType('panel');

        // Top Games - Admin Dashboard
        $topGamesAdminModule = $modules->newEntity();
        $topGamesAdminModule->setModule('games');
        $topGamesAdminModule->setActive(1);
        $topGamesAdminModule->setPosition('admin_dashboard');
        $topGamesAdminModule->setTitle('Top Games');
        $topGamesAdminModule->setShowHeader(1);
        $topGamesAdminModule->setTemplateType('list_panel');
        $topGamesAdminModule->setSettingsArray(['order' => 'top-hits']);

        // User Info - User Profile
        $userInfoModule = $modules->newEntity();
        $userInfoModule->setModule('user_info');
        $userInfoModule->setActive(1);
        $userInfoModule->setPosition('user_profile_main');
        $userInfoModule->setTitle('User Info');
        $userInfoModule->setShowHeader(1);
        $userInfoModule->setTemplateType('panel');

        // Liked Games - User Profile
        $likedWallpapersModule = $modules->newEntity();
        $likedWallpapersModule->setModule('games');
        $likedWallpapersModule->setActive(1);
        $likedWallpapersModule->setPosition('user_profile_main');
        $likedWallpapersModule->setTitle('Liked Games');
        $likedWallpapersModule->setShowHeader(1);
        $likedWallpapersModule->setTemplateType('panel');
        $likedWallpapersModule->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 6, 'filter' => 'likes']);

        $modules->insert([$updatesModule, $reportsModule, $avsNewsModule, $userInfoModule, $likedWallpapersModule, $topGamesAdminModule]);
    }

    public function blogDefaults()
    {
        $blogPosts = $this->modelFactory->create('AVCMS\Bundles\Blog\Model\BlogPosts');

        $post = $blogPosts->newEntity();
        $post->setTitle('Welcome to AVCMS');
        $post->setBody('<p>Welcome to the blog!</p>
            <p>This is just a first blog post to show the blog system. You can edit or delete it from the admin panel.</p>');
        $post->setSlug('welcome-to-avcms');
        $post->setPublishDate(time());

        $blogPosts->save($post);
    }

    public function gameDefaults()
    {
        $gameCategories = $this->modelFactory->create('AVCMS\Bundles\Games\Model\GameCategories');

        $firstCategory = $gameCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $gameCategories->insert($firstCategory);
    }
}
