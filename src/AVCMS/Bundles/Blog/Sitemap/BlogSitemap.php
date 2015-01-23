<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 15:58
 */

namespace AVCMS\Bundles\Blog\Sitemap;

use AVCMS\Bundles\Blog\Model\BlogPosts;
use AVCMS\Core\Sitemaps\SitemapInterface;
use AVCMS\Core\Sitemaps\SitemapLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BlogSitemap implements SitemapInterface
{
    private $blogPosts;

    private $urlGenerator;

    public function __construct(BlogPosts $blogPosts, UrlGeneratorInterface $urlGenerator)
    {
        $this->blogPosts = $blogPosts;
        $this->urlGenerator = $urlGenerator;
    }

    public function getId()
    {
        return 'blog-posts';
    }

    public function requiresUpdate($lastRunTime)
    {
        $changed = $this->blogPosts->query()
            ->where('date_added', '>', $lastRunTime)
            ->orWhere('date_edited', '>', $lastRunTime)
            ->orWhere(function($q) use ($lastRunTime) {
                $q->where('publish_date', '>', $lastRunTime);
                $q->where('publish_date', '<=', time());
            })
            ->count()
        ;

        return ($changed > 0 ? true : false);
    }

    public function getSitemapLinks($page)
    {
        /**
         * @var \AVCMS\Bundles\Wallpapers\Model\Wallpaper[] $blogPosts
         */
        $finder = $this->blogPosts->find()
            ->setResultsPerPage(self::LINKS_PER_PAGE)
            ->page($page)
            ->published()
        ;

        $finder->getQuery()->select(['date_added', 'date_edited', 'slug']);

        $blogPosts = $finder->get();

        $links = [];

        foreach ($blogPosts as $wallpaper) {
            try {
                $url = $this->urlGenerator->generate('blog_post', ['slug' => $wallpaper->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

                $timestamp = ($wallpaper->getDateEdited() ? $wallpaper->getDateEdited() : $wallpaper->getDateAdded());
                $dateModified = new \DateTime();
                $dateModified->setTimestamp($timestamp);
            }
            catch (\Exception $e) {
                continue;
            }

            $links[] = new SitemapLink($url, $dateModified);
        }

        unset($blogPosts);

        return $links;
    }
}
