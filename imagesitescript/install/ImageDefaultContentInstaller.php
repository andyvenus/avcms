<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class ImageDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults'],
            'CmsFoundation' => ['1.0' => 'cmsDefaults'],
            'Images' => ['1.0' => 'imageDefaults'],
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

        // Newest Images - Sidebar
        $newestImagesSidebar = $modules->newEntity();
        $newestImagesSidebar->setModule('images');
        $newestImagesSidebar->setActive(1);
        $newestImagesSidebar->setPosition('sidebar');
        $newestImagesSidebar->setTitle('Latest Images');
        $newestImagesSidebar->setShowHeader(1);
        $newestImagesSidebar->setTemplateType('list_panel');
        $newestImagesSidebar->setCacheTime(3600);

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
        $newestImagesHome->setSettingsArray(['layout' => 'thumbnails', 'columns' => 3, 'limit' => 18]);
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

        // Image Tags - Sidebar
        $imageTags = $modules->newEntity();
        $imageTags->setModule('image_tags');
        $imageTags->setActive(1);
        $imageTags->setPosition('sidebar');
        $imageTags->setTitle('Image Tags');
        $imageTags->setShowHeader(1);
        $imageTags->setTemplateType('panel');
        $imageTags->setCacheTime(43200);

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

        $modules->insert([$newestImagesSidebar, $featuredImagesHome, $newestImagesHome, $imageTags, $newestPostsModule, $linksModule, $shareModule]);

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
        $imageStatsModule = $modules->newEntity();
        $imageStatsModule->setModule('image_stats');
        $imageStatsModule->setActive(1);
        $imageStatsModule->setPosition('admin_dashboard');
        $imageStatsModule->setTitle('Game Stats');
        $imageStatsModule->setShowHeader(1);
        $imageStatsModule->setTemplateType('panel');

        // Top Images - Admin Dashboard
        $topImagesAdminModule = $modules->newEntity();
        $topImagesAdminModule->setModule('images');
        $topImagesAdminModule->setActive(1);
        $topImagesAdminModule->setPosition('admin_dashboard');
        $topImagesAdminModule->setTitle('Top Images');
        $topImagesAdminModule->setShowHeader(1);
        $topImagesAdminModule->setTemplateType('list_panel');
        $topImagesAdminModule->setSettingsArray(['order' => 'top-hits']);

        // User Info - User Profile
        $userInfoModule = $modules->newEntity();
        $userInfoModule->setModule('user_info');
        $userInfoModule->setActive(1);
        $userInfoModule->setPosition('user_profile_main');
        $userInfoModule->setTitle('User Info');
        $userInfoModule->setShowHeader(1);
        $userInfoModule->setTemplateType('panel');

        // Liked Images - User Profile
        $likedImagesModule = $modules->newEntity();
        $likedImagesModule->setModule('images');
        $likedImagesModule->setActive(1);
        $likedImagesModule->setPosition('user_profile_main');
        $likedImagesModule->setTitle('Liked Images');
        $likedImagesModule->setShowHeader(1);
        $likedImagesModule->setTemplateType('panel');
        $likedImagesModule->setSettingsArray(['layout' => 'thumbnails', 'columns' => 2, 'limit' => 6, 'filter' => 'likes']);

        $modules->insert([$reportsModule, $imageStatsModule, $topImagesAdminModule, $userInfoModule, $likedImagesModule]);
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
