<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 19:35
 */

namespace AVCMS\Core\Sitemaps;

class SitemapsManager
{
    /**
     * @var SitemapInterface[]
     */
    protected $sitemaps = [];

    protected $sitemapWriter;

    protected $cacheFile;

    protected $lastGenerationTime;

    public function __construct(SitemapWriter $sitemapWriter, $cacheDir)
    {
        $this->sitemapWriter = $sitemapWriter;
        $this->cacheFile = $cacheDir.'/sitemap_last_generation.txt';
    }

    public function getLastGenerationTime()
    {
        if (!isset($this->lastGenerationTime)) {
            if (!file_exists($this->cacheFile)) {
                return $this->lastGenerationTime = 0;
            } else {
                return $this->lastGenerationTime = file_get_contents($this->cacheFile);
            }
        }

        return $this->lastGenerationTime;
    }

    public function addSitemap(SitemapInterface $sitemap)
    {
        $this->sitemaps[$sitemap->getId()] = $sitemap;
    }

    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    public function writeSitemaps()
    {
        foreach ($this->sitemaps as $sitemap) {
            if ($sitemap->requiresUpdate($this->getLastGenerationTime())) {
                $this->sitemapWriter->writeSitemap($sitemap);
                $sitemapChanged = true;
            }
        }

        if (isset($sitemapChanged)) {
            $this->sitemapWriter->writeSitemapIndex($this->sitemaps);
        }

        file_put_contents($this->cacheFile, time());
    }

    public function getIndexFileContents()
    {
        return $this->sitemapWriter->getSitemapIndex();
    }
}
