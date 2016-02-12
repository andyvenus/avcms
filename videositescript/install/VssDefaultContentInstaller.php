<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class VssDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults'],
            'Videos' => ['1.0' => 'videoDefaults']
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

        // Newest Videos - Sidebar
        $newestVideosSidebar = $modules->newEntity();
        $newestVideosSidebar->setModule('videos');
        $newestVideosSidebar->setActive(1);
        $newestVideosSidebar->setPosition('sidebar');
        $newestVideosSidebar->setTitle('Latest Videos');
        $newestVideosSidebar->setShowHeader(1);
        $newestVideosSidebar->setTemplateType('list_panel');
        $newestVideosSidebar->setCacheTime(3600);

        // Featured Videos - Homepage
        $featuredVideosHome = $modules->newEntity();
        $featuredVideosHome->setModule('videos');
        $featuredVideosHome->setActive(1);
        $featuredVideosHome->setPosition('homepage');
        $featuredVideosHome->setTitle('Featured Videos');
        $featuredVideosHome->setShowHeader(1);
        $featuredVideosHome->setTemplateType('content');
        $featuredVideosHome->setSettingsArray(['filter' => 'featured', 'layout' => 'thumbnails', 'columns' => 4, 'limit' => 8]);
        $featuredVideosHome->setCacheTime(3600);

        // Newest Videos - Homepage
        $newestVideosHome = $modules->newEntity();
        $newestVideosHome->setModule('videos');
        $newestVideosHome->setActive(1);
        $newestVideosHome->setPosition('homepage');
        $newestVideosHome->setTitle('Newest Videos');
        $newestVideosHome->setShowHeader(1);
        $newestVideosHome->setTemplateType('content');
        $newestVideosHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 18]);
        $newestVideosHome->setCacheTime(3600);

        // Newest Blog Posts - Sidebar
        $newestPostsModule = $modules->newEntity();
        $newestPostsModule->setModule('blog_posts');
        $newestPostsModule->setActive(1);
        $newestPostsModule->setPosition('sidebar');
        $newestPostsModule->setTitle('Blog Posts');
        $newestPostsModule->setShowHeader(1);
        $newestPostsModule->setTemplateType('list_panel');
        $newestPostsModule->setCacheTime(3600);

        // Video Tags - Sidebar
        $videoTags = $modules->newEntity();
        $videoTags->setModule('video_tags');
        $videoTags->setActive(1);
        $videoTags->setPosition('sidebar');
        $videoTags->setTitle('Video Tags');
        $videoTags->setShowHeader(1);
        $videoTags->setTemplateType('panel');
        $videoTags->setCacheTime(43200);

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

        $modules->insert([$newestVideosSidebar, $featuredVideosHome, $newestVideosHome, $newestPostsModule, $videoTags, $linksModule, $shareModule]);

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

        // Video Stats - Admin Dashboard
        $videoStatsModule = $modules->newEntity();
        $videoStatsModule->setModule('video_stats');
        $videoStatsModule->setActive(1);
        $videoStatsModule->setPosition('admin_dashboard');
        $videoStatsModule->setTitle('Video Stats');
        $videoStatsModule->setShowHeader(1);
        $videoStatsModule->setTemplateType('panel');

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

        // Liked Videos - User Profile
        $likedVideosModule = $modules->newEntity();
        $likedVideosModule->setModule('videos');
        $likedVideosModule->setActive(1);
        $likedVideosModule->setPosition('user_profile_main');
        $likedVideosModule->setTitle('Liked Videos');
        $likedVideosModule->setShowHeader(1);
        $likedVideosModule->setTemplateType('panel');
        $likedVideosModule->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 6, 'filter' => 'likes']);

        $modules->insert([$updatesModule, $reportsModule, $avsNewsModule, $videoStatsModule, $userInfoModule, $likedVideosModule, $topVideosAdminModule]);
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

    public function videoDefaults()
    {
        $videoCategories = $this->modelFactory->create('AVCMS\Bundles\Videos\Model\VideoCategories');

        $firstCategory = $videoCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $videoCategories->insert($firstCategory);
    }
}
