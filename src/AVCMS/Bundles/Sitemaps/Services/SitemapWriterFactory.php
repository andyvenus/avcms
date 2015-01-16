<?php
/**
 * User: Andy
 * Date: 16/01/15
 * Time: 15:48
 */

namespace AVCMS\Bundles\Sitemaps\Services;

use AVCMS\Core\Kernel\SiteRootUrl;
use AVCMS\Core\Sitemaps\SitemapWriter;

class SitemapWriterFactory
{
    protected $sitemapUri;

    protected $sitemapDir;

    public function __construct(SiteRootUrl $rootUrl, $sitemapDir, $sitemapUri)
    {
        $this->sitemapUri = $rootUrl->getSiteUrl().$sitemapUri;
        $this->sitemapDir = $sitemapDir;
    }

    public function create()
    {
        return new SitemapWriter($this->sitemapUri, $this->sitemapDir);
    }
}
