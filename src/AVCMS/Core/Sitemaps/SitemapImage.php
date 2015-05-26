<?php
/**
 * User: Andy
 * Date: 26/05/15
 * Time: 13:05
 */

namespace AVCMS\Core\Sitemaps;

class SitemapImage
{
    protected $url;

    protected $title;

    public function __construct($url, $title = null)
    {
        $this->url = $url;
        $this->title = $title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
