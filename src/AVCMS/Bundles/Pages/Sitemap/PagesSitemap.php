<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 16:06
 */

namespace AVCMS\Bundles\Pages\Sitemap;

use AVCMS\Bundles\Pages\Model\Pages;
use AVCMS\Core\Sitemaps\SitemapInterface;
use AVCMS\Core\Sitemaps\SitemapLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PagesSitemap implements SitemapInterface
{
    private $pages;

    private $urlGenerator;

    public function __construct(Pages $pages, UrlGeneratorInterface $urlGenerator)
    {
        $this->pages = $pages;
        $this->urlGenerator = $urlGenerator;
    }

    public function getId()
    {
        return 'pages';
    }

    public function requiresUpdate($lastRunTime)
    {
        $changed = $this->pages->query()
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
         * @var \AVCMS\Bundles\Pages\Model\Page[] $pages
         */
        $finder = $this->pages->find()
            ->setResultsPerPage(self::LINKS_PER_PAGE)
            ->page($page)
            ->published()
        ;

        $finder->getQuery()->select(['date_added', 'date_edited', 'slug']);

        $pages = $finder->get();

        $links = [];

        foreach ($pages as $pageItem) {
            try {
                $url = $this->urlGenerator->generate('page', ['slug' => $pageItem->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

                $timestamp = ($pageItem->getDateEdited() ? $pageItem->getDateEdited() : $pageItem->getDateAdded());
                $dateModified = new \DateTime();
                $dateModified->setTimestamp($timestamp);
            }
            catch (\Exception $e) {
                continue;
            }

            $links[] = new SitemapLink($url, $dateModified);
        }

        unset($pages);

        return $links;
    }
}
