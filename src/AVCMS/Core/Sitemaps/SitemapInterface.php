<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 19:36
 */

namespace AVCMS\Core\Sitemaps;

interface SitemapInterface
{
    const LINKS_PER_PAGE = 50000;

    public function getId();

    public function requiresUpdate($lastGenerationTime);

    public function getSitemapLinks($page);
}
