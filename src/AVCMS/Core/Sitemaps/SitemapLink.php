<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 19:46
 */

namespace AVCMS\Core\Sitemaps;

class SitemapLink
{
    /**
     * @var string
     */
    protected $link;

    /**
     * @var null|\DateTime
     */
    protected $lastChanged;

    /**
     * @var null|array
     */
    protected $images;

    public function __construct($link, \DateTime $lastChanged = null, array $images = null)
    {
        $this->link = $link;
        $this->lastChanged = $lastChanged;
        $this->images = $images;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getLastChanged()
    {
        return $this->lastChanged;
    }

    public function getImages()
    {
        return $this->images;
    }
}
