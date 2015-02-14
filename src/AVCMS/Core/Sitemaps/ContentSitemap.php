<?php
/**
 * User: Andy
 * Date: 14/02/15
 * Time: 11:47
 */

namespace AVCMS\Core\Sitemaps;

use AVCMS\Core\Model\ContentModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContentSitemap implements SitemapInterface
{
    protected $model;

    protected $urlGenerator;

    protected $routeName;

    public function __construct(ContentModel $model, UrlGeneratorInterface $urlGenerator, $routeName)
    {
        $this->model = $model;
        $this->urlGenerator = $urlGenerator;
        $this->routeName = $routeName;
    }

    public function getId()
    {
        return $this->model->getTable();
    }

    public function requiresUpdate($lastRunTime)
    {
        $changed = $this->model->query()
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
         * @var \AVCMS\Core\Model\ContentEntity[] $items
         */
        $finder = $this->model->find()
            ->setResultsPerPage(self::LINKS_PER_PAGE)
            ->page($page)
            ->published()
        ;

        $finder->getQuery()->select(['date_added', 'date_edited', 'slug']);

        $items = $finder->get();

        $links = [];

        foreach ($items as $item) {
            try {
                $url = $this->urlGenerator->generate($this->routeName, ['slug' => $item->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

                $timestamp = ($item->getDateEdited() ? $item->getDateEdited() : $item->getDateAdded());
                $dateModified = new \DateTime();
                $dateModified->setTimestamp($timestamp);
            }
            catch (\Exception $e) {
                continue;
            }

            $links[] = new SitemapLink($url, $dateModified);
        }

        return $links;
    }
}
