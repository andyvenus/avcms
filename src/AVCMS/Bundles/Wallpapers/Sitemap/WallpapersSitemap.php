<?php
/**
 * User: Andy
 * Date: 16/01/15
 * Time: 11:51
 */

namespace AVCMS\Bundles\Wallpapers\Sitemap;

use AVCMS\Bundles\Wallpapers\Model\Wallpapers;
use AVCMS\Core\Sitemaps\SitemapInterface;
use AVCMS\Core\Sitemaps\SitemapLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WallpapersSitemap implements SitemapInterface
{
    protected $wallpapers;

    protected $urlGenerator;

    public function __construct(Wallpapers $wallpapers, UrlGeneratorInterface $urlGenerator)
    {
        $this->wallpapers = $wallpapers;
        $this->urlGenerator = $urlGenerator;
    }

    public function getId()
    {
        return 'wallpapers';
    }

    public function requiresUpdate($lastRunTime)
    {
        $changed = $this->wallpapers->query()
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
         * @var \AVCMS\Bundles\Wallpapers\Model\Wallpaper[] $wallpapers
         */
        $finder = $this->wallpapers->find()
            ->setResultsPerPage(self::LINKS_PER_PAGE)
            ->page($page)
            ->published()
        ;

        $finder->getQuery()->select(['date_added', 'date_edited', 'slug']);

        $wallpapers = $finder->get();

        $links = [];

        foreach ($wallpapers as $wallpaper) {
            try {
                $url = $this->urlGenerator->generate('wallpaper_details', ['slug' => $wallpaper->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

                $timestamp = ($wallpaper->getDateEdited() ? $wallpaper->getDateEdited() : $wallpaper->getDateAdded());
                $dateModified = new \DateTime();
                $dateModified->setTimestamp($timestamp);
            }
            catch (\Exception $e) {
                continue;
            }

            $links[] = new SitemapLink($url, $dateModified);
        }

        unset($wallpapers);

        return $links;
    }
}
