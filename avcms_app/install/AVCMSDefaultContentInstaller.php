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
            'Games' => ['1.0' => 'gameDefaults'],
            'Images' => ['1.0' => 'imageDefaults']
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

        // Featured Images - Homepage
        $featuredImagesHome = $modules->newEntity();
        $featuredImagesHome->setModule('images');
        $featuredImagesHome->setActive(1);
        $featuredImagesHome->setPosition('homepage');
        $featuredImagesHome->setTitle('Featured Images');
        $featuredImagesHome->setShowHeader(1);
        $featuredImagesHome->setTemplateType('content');
        $featuredImagesHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $featuredImagesHome->setCacheTime(3600);

        // Newest Images - Homepage
        $newestImagesHome = $modules->newEntity();
        $newestImagesHome->setModule('images');
        $newestImagesHome->setActive(1);
        $newestImagesHome->setPosition('homepage');
        $newestImagesHome->setTitle('Newest Images');
        $newestImagesHome->setShowHeader(1);
        $newestImagesHome->setTemplateType('content');
        $newestImagesHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $newestImagesHome->setCacheTime(3600);

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

        // Game Tags - Sidebar
        $gameTags = $modules->newEntity();
        $gameTags->setModule('game_tags');
        $gameTags->setActive(1);
        $gameTags->setPosition('sidebar');
        $gameTags->setTitle('Game Tags');
        $gameTags->setShowHeader(1);
        $gameTags->setTemplateType('panel');
        $gameTags->setCacheTime(43200);

        // Image Tags - Sidebar
        $imageTags = $modules->newEntity();
        $imageTags->setModule('image_tags');
        $imageTags->setActive(1);
        $imageTags->setPosition('sidebar');
        $imageTags->setTitle('Image Tags');
        $imageTags->setShowHeader(1);
        $imageTags->setTemplateType('panel');
        $imageTags->setCacheTime(43200);

        // Featured Videos - Homepage
        $featuredVideosHome = $modules->newEntity();
        $featuredVideosHome->setModule('videos');
        $featuredVideosHome->setActive(1);
        $featuredVideosHome->setPosition('homepage');
        $featuredVideosHome->setTitle('Featured Videos');
        $featuredVideosHome->setShowHeader(1);
        $featuredVideosHome->setTemplateType('content');
        $featuredVideosHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $featuredVideosHome->setCacheTime(3600);

        // Newest Videos - Homepage
        $newestVideosHome = $modules->newEntity();
        $newestVideosHome->setModule('videos');
        $newestVideosHome->setActive(1);
        $newestVideosHome->setPosition('homepage');
        $newestVideosHome->setTitle('Newest Videos');
        $newestVideosHome->setShowHeader(1);
        $newestVideosHome->setTemplateType('content');
        $newestVideosHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 3, 'limit' => 6]);
        $newestVideosHome->setCacheTime(3600);

        // Video Tags - Sidebar
        $videoTags = $modules->newEntity();
        $videoTags->setModule('video_tags');
        $videoTags->setActive(1);
        $videoTags->setPosition('sidebar');
        $videoTags->setTitle('Video Tags');
        $videoTags->setShowHeader(1);
        $videoTags->setTemplateType('panel');
        $videoTags->setCacheTime(43200);

        $modules->insert([$featuredVideosHome, $newestVideosHome, $videoTags, $featuredGamesHome, $newestGamesHome, $featuredImagesHome, $newestImagesHome, $newestWallpapersSidebar, $featuredWallpapersHome, $newestWallpapersHome, $wallpaperTags, $newestPostsModule, $gameTags, $imageTags]);

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

        // Top Images - Admin Dashboard
        $topImagesAdminModule = $modules->newEntity();
        $topImagesAdminModule->setModule('images');
        $topImagesAdminModule->setActive(1);
        $topImagesAdminModule->setPosition('admin_dashboard');
        $topImagesAdminModule->setTitle('Top Images');
        $topImagesAdminModule->setShowHeader(1);
        $topImagesAdminModule->setTemplateType('list_panel');
        $topImagesAdminModule->setSettingsArray(['order' => 'top-hits']);

        // Top Videos - Admin Dashboard
        $topVideosAdminModule = $modules->newEntity();
        $topVideosAdminModule->setModule('videos');
        $topVideosAdminModule->setActive(1);
        $topVideosAdminModule->setPosition('admin_dashboard');
        $topVideosAdminModule->setTitle('Top Videos');
        $topVideosAdminModule->setShowHeader(1);
        $topVideosAdminModule->setTemplateType('list_panel');
        $topVideosAdminModule->setSettingsArray(['order' => 'top-hits']);

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

        $modules->insert([$updatesModule, $reportsModule, $avsNewsModule, $topWallpapersAdminModule, $topVideosAdminModule, $userInfoModule, $likedWallpapersModule, $topGamesAdminModule, $topImagesAdminModule]);
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
        $post->setDateAdded(time());

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

    public function imageDefaults()
    {
        $imageCategories = $this->modelFactory->create('AVCMS\Bundles\Images\Model\ImageCategories');

        $firstCategory = $imageCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $imageCategories->insert($firstCategory);
    }
}
