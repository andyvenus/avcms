<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class AVCMSDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults'],
            'Wallpapers' => ['1.0' => 'wallpaperDefaults'],
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

        $categoriesItem = $menuItems->newEntity();
        $categoriesItem->setMenu('frontend');
        $categoriesItem->setType('wallpaper_categories');
        $categoriesItem->setLabel('Wallpapers');
        $categoriesItem->setSettings(['display' => 'child']);
        $categoriesItem->setIcon('glyphicon glyphicon-picture');

        $gameCategoriesItem = $menuItems->newEntity();
        $gameCategoriesItem->setMenu('frontend');
        $gameCategoriesItem->setType('game_categories');
        $gameCategoriesItem->setLabel('Games');
        $gameCategoriesItem->setSettings(['display' => 'child']);
        $gameCategoriesItem->setIcon('glyphicon glyphicon-tower');

        $blogItem = $menuItems->newEntity();
        $blogItem->setMenu('frontend');
        $blogItem->setType('route');
        $blogItem->setLabel('Blog');
        $blogItem->setSettings(['route' => 'blog_home']);
        $blogItem->setIcon('glyphicon glyphicon-pencil');

        $submitItem = $menuItems->newEntity();
        $submitItem->setMenu('frontend');
        $submitItem->setType('route');
        $submitItem->setLabel('Submit Wallpaper');
        $submitItem->setSettings(['route' => 'wallpaper_submit']);
        $submitItem->setIcon('glyphicon glyphicon-upload');

        $submitItem = $menuItems->newEntity();
        $submitItem->setMenu('frontend');
        $submitItem->setType('route');
        $submitItem->setLabel('Submit Game');
        $submitItem->setSettings(['route' => 'submit_game']);
        $submitItem->setIcon('glyphicon glyphicon-upload');

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

        $menuItems->insert([$homeItem, $categoriesItem, $submitItem, $contactItem, $linksItem, $gameCategoriesItem, $blogItem]);

        // Modules
        $modules = $this->modelFactory->create('AVCMS\Bundles\CmsFoundation\Model\Modules');

        // Newest Wallpapers - Sidebar
        $newestWallpapersSidebar = $modules->newEntity();
        $newestWallpapersSidebar->setModule('wallpapers');
        $newestWallpapersSidebar->setActive(1);
        $newestWallpapersSidebar->setPosition('sidebar');
        $newestWallpapersSidebar->setTitle('Latest Wallpapers');
        $newestWallpapersSidebar->setShowHeader(1);
        $newestWallpapersSidebar->setTemplateType('list_panel');
        $newestWallpapersSidebar->setCacheTime(3600);

        // Featured Wallpapers - Homepage
        $featuredWallpapersHome = $modules->newEntity();
        $featuredWallpapersHome->setModule('wallpapers');
        $featuredWallpapersHome->setActive(1);
        $featuredWallpapersHome->setPosition('homepage');
        $featuredWallpapersHome->setTitle('Featured Wallpapers');
        $featuredWallpapersHome->setShowHeader(1);
        $featuredWallpapersHome->setTemplateType('content');
        $featuredWallpapersHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $featuredWallpapersHome->setCacheTime(3600);

        // Newest Wallpapers - Homepage
        $newestWallpapersHome = $modules->newEntity();
        $newestWallpapersHome->setModule('wallpapers');
        $newestWallpapersHome->setActive(1);
        $newestWallpapersHome->setPosition('homepage');
        $newestWallpapersHome->setTitle('Newest Wallpapers');
        $newestWallpapersHome->setShowHeader(1);
        $newestWallpapersHome->setTemplateType('content');
        $newestWallpapersHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $newestWallpapersHome->setCacheTime(3600);

        // Featured Games - Homepage
        $featuredGamesHome = $modules->newEntity();
        $featuredGamesHome->setModule('games');
        $featuredGamesHome->setActive(1);
        $featuredGamesHome->setPosition('homepage');
        $featuredGamesHome->setTitle('Featured Games');
        $featuredGamesHome->setShowHeader(1);
        $featuredGamesHome->setTemplateType('content');
        $featuredGamesHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $featuredGamesHome->setCacheTime(3600);

        // Newest Games - Homepage
        $newestGamesHome = $modules->newEntity();
        $newestGamesHome->setModule('games');
        $newestGamesHome->setActive(1);
        $newestGamesHome->setPosition('homepage');
        $newestGamesHome->setTitle('Newest Games');
        $newestGamesHome->setShowHeader(1);
        $newestGamesHome->setTemplateType('content');
        $newestGamesHome->setSettingsArray(['layout' => 'details', 'columns' => 2, 'limit' => 6]);
        $newestGamesHome->setCacheTime(3600);

        // Newest Blog Posts - Sidebar
        $newestPostsModule = $modules->newEntity();
        $newestPostsModule->setModule('blog_posts');
        $newestPostsModule->setActive(1);
        $newestPostsModule->setPosition('sidebar');
        $newestPostsModule->setTitle('Latest Posts');
        $newestPostsModule->setShowHeader(1);
        $newestPostsModule->setTemplateType('list_panel');
        $newestPostsModule->setCacheTime(3600);

        // Wallpaper Tags - Sidebar
        $wallpaperTags = $modules->newEntity();
        $wallpaperTags->setModule('wallpaper_tags');
        $wallpaperTags->setActive(1);
        $wallpaperTags->setPosition('sidebar');
        $wallpaperTags->setTitle('Wallpaper Tags');
        $wallpaperTags->setShowHeader(1);
        $wallpaperTags->setTemplateType('panel');
        $wallpaperTags->setCacheTime(43200);

        // Game Tags - Sidebar
        $gameTags = $modules->newEntity();
        $gameTags->setModule('game_tags');
        $gameTags->setActive(1);
        $gameTags->setPosition('sidebar');
        $gameTags->setTitle('Game Tags');
        $gameTags->setShowHeader(1);
        $gameTags->setTemplateType('panel');
        $gameTags->setCacheTime(43200);

        $modules->insert([$newestWallpapersSidebar, $featuredWallpapersHome, $newestWallpapersHome, $featuredGamesHome, $newestGamesHome, $wallpaperTags, $newestPostsModule, $gameTags]);

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

        // Top Wallpapers - Admin Dashboard
        $topWallpapersAdminModule = $modules->newEntity();
        $topWallpapersAdminModule->setModule('wallpapers');
        $topWallpapersAdminModule->setActive(1);
        $topWallpapersAdminModule->setPosition('admin_dashboard');
        $topWallpapersAdminModule->setTitle('Top Wallpapers');
        $topWallpapersAdminModule->setShowHeader(1);
        $topWallpapersAdminModule->setTemplateType('list_panel');
        $topWallpapersAdminModule->setSettingsArray(['order' => 'top-hits']);

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

        // Liked Wallpapers - User Profile
        $likedWallpapersModule = $modules->newEntity();
        $likedWallpapersModule->setModule('liked_wallpapers');
        $likedWallpapersModule->setActive(1);
        $likedWallpapersModule->setPosition('user_profile_main');
        $likedWallpapersModule->setTitle('Liked Wallpapers');
        $likedWallpapersModule->setShowHeader(1);
        $likedWallpapersModule->setTemplateType('panel');
        $likedWallpapersModule->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 6]);

        $modules->insert([$updatesModule, $reportsModule, $avsNewsModule, $topWallpapersAdminModule, $userInfoModule, $likedWallpapersModule, $topGamesAdminModule]);
    }

    public function blogDefaults()
    {
        $blogPosts = $this->modelFactory->create('AVCMS\Bundles\Blog\Model\BlogPosts');

        $post = $blogPosts->newEntity();
        $post->setTitle('Welcome to AVCMS');
        $post->setBody('<p>Welcome to AVCMS, a new modular content management system.</p>
            <p>This is just a first blog post to show the blog system. You can edit or delete it from the admin panel.</p>');
        $post->setSlug('welcome-to-avcms');
        $post->setPublishDate(time());

        $blogPosts->save($post);
    }

    public function wallpaperDefaults()
    {
        $wallpapers = $this->modelFactory->create('AVCMS\Bundles\Wallpapers\Model\Wallpapers');

        $wallpaperCategories = $this->modelFactory->create('AVCMS\Bundles\Wallpapers\Model\WallpaperCategories');

        $firstCategory = $wallpaperCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $wallpaperCategories->insert($firstCategory);
    }

    public function gameDefaults()
    {
        $games = $this->modelFactory->create('AVCMS\Bundles\Games\Model\Games');

        $gameCategories = $this->modelFactory->create('AVCMS\Bundles\Games\Model\GameCategories');

        $firstCategory = $gameCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $gameCategories->insert($firstCategory);
    }
}
