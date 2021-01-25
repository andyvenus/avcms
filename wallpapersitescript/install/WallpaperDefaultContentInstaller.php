<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class WallpaperDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults'],
            'Wallpapers' => ['1.0' => 'wallpaperDefaults'],
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
        $homeItem->setTranslatable(1);

        $contactItem = $menuItems->newEntity();
        $contactItem->setMenu('frontend_footer');
        $contactItem->setType('route');
        $contactItem->setLabel('Contact Us');
        $contactItem->setSettings(['route' => 'contact_us']);
        $contactItem->setIcon('glyphicon glyphicon-envelope');
        $contactItem->setTranslatable(1);

        $linksItem = $menuItems->newEntity();
        $linksItem->setMenu('frontend_footer');
        $linksItem->setType('route');
        $linksItem->setLabel('Link Exchange');
        $linksItem->setSettings(['route' => 'links']);
        $linksItem->setIcon('glyphicon glyphicon-link');
        $linksItem->setTranslatable(1);

        $membersItem = $menuItems->newEntity();
        $membersItem->setMenu('frontend_footer');
        $membersItem->setType('route');
        $membersItem->setLabel('Members');
        $membersItem->setSettings(['route' => 'member_list']);
        $membersItem->setIcon('glyphicon glyphicon-user');
        $membersItem->setTranslatable(1);

        $menuItems->insert([$homeItem, $contactItem, $linksItem, $membersItem]);

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
        $newestWallpapersHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 3, 'limit' => 18]);
        $newestWallpapersHome->setCacheTime(3600);

        // Newest Blog Posts - Sidebar
        $newestPostsModule = $modules->newEntity();
        $newestPostsModule->setModule('blog_posts');
        $newestPostsModule->setActive(1);
        $newestPostsModule->setPosition('sidebar');
        $newestPostsModule->setTitle('Blog Posts');
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

        $modules->insert([$newestWallpapersSidebar, $featuredWallpapersHome, $newestWallpapersHome, $wallpaperTags, $newestPostsModule, $linksModule, $shareModule]);

        // Updates - Admin Dashboard
        // $updatesModule = $modules->newEntity();
        // $updatesModule->setModule('avcms_updates');
        // $updatesModule->setActive(1);
        // $updatesModule->setPosition('admin_dashboard');
        // $updatesModule->setTitle('Updates');
        // $updatesModule->setShowHeader(1);
        // $updatesModule->setTemplateType('panel');
        // $updatesModule->setCacheTime(999999);

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
        // $avsNewsModule = $modules->newEntity();
        // $avsNewsModule->setModule('avcms_news');
        // $avsNewsModule->setActive(1);
        // $avsNewsModule->setPosition('admin_dashboard');
        // $avsNewsModule->setTitle('AV Scripts News');
        // $avsNewsModule->setShowHeader(1);
        // $avsNewsModule->setTemplateType('panel');

        // Game Stats - Admin Dashboard
        $wallpaperStatsModule = $modules->newEntity();
        $wallpaperStatsModule->setModule('wallpaper_stats');
        $wallpaperStatsModule->setActive(1);
        $wallpaperStatsModule->setPosition('admin_dashboard');
        $wallpaperStatsModule->setTitle('Game Stats');
        $wallpaperStatsModule->setShowHeader(1);
        $wallpaperStatsModule->setTemplateType('panel');

        // Top Wallpapers - Admin Dashboard
        $topWallpapersAdminModule = $modules->newEntity();
        $topWallpapersAdminModule->setModule('wallpapers');
        $topWallpapersAdminModule->setActive(1);
        $topWallpapersAdminModule->setPosition('admin_dashboard');
        $topWallpapersAdminModule->setTitle('Top Wallpapers');
        $topWallpapersAdminModule->setShowHeader(1);
        $topWallpapersAdminModule->setTemplateType('list_panel');
        $topWallpapersAdminModule->setSettingsArray(['order' => 'top-hits']);

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
        $likedWallpapersModule->setModule('wallpapers');
        $likedWallpapersModule->setActive(1);
        $likedWallpapersModule->setPosition('user_profile_main');
        $likedWallpapersModule->setTitle('Liked Wallpapers');
        $likedWallpapersModule->setShowHeader(1);
        $likedWallpapersModule->setTemplateType('panel');
        $likedWallpapersModule->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 6, 'filter' => 'likes']);

        $modules->insert([$reportsModule, $wallpaperStatsModule, $topWallpapersAdminModule, $userInfoModule, $likedWallpapersModule]);
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

    public function wallpaperDefaults()
    {
        $wallpaperCategories = $this->modelFactory->create('AVCMS\Bundles\Wallpapers\Model\WallpaperCategories');

        $firstCategory = $wallpaperCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $wallpaperCategories->insert($firstCategory);
    }
}
