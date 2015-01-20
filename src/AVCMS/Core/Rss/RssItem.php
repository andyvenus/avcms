<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 11:45
 */

namespace AVCMS\Core\Rss;

class RssItem
{
    protected $title;

    protected $link;

    protected $description;

    /**
     * @var \DateTime|null
     */
    protected $pubDate;

    public function __construct($title, $link, \DateTime $pubDate = null, $description = null)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->pubDate = $pubDate;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPubDate()
    {
        if ($this->pubDate instanceof \DateTime) {
            return $this->pubDate->format(\DateTime::RSS);
        }

        return $this->pubDate;
    }
}
