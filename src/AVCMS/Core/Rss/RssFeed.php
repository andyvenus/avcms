<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 11:31
 */

namespace AVCMS\Core\Rss;

class RssFeed
{
    protected $filePath;

    protected $title;

    protected $link;

    protected $description;

    /**
     * @var RssItem[]
     */
    protected $items = [];

    public function __construct($title, $link = null, $description = null, $filePath = null)
    {
        $this->filePath = $filePath;
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param RssItem[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function addItem(RssItem $item)
    {
        $this->items[] = $item;
    }

    public function build()
    {
        $writer = new \XMLWriter();

        if ($this->filePath) {
            $writer->openUri($this->filePath);
        }
        else {
            $writer->openMemory();
        }

        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);
        $writer->startElement('rss');
        $writer->writeAttribute('version', '2.0');

        $writer->startElement('channel');

        if (isset($this->title)) {
            $writer->writeElement('title', $this->title);
        }
        if (isset($this->link)) {
            $writer->writeElement('link', $this->link);
        }
        if (isset($this->description)) {
            $writer->writeElement('description', $this->description);
        }

        foreach ($this->items as $item) {
            $writer->startElement('item');

            $writer->writeElement('title', $item->getTitle());
            $writer->writeElement('link', $item->getLink());

            if ($item->getDescription() !== null) {
                $writer->writeElement('description', $item->getDescription());
            }

            if ($item->getPubDate() !== null) {
                $writer->writeElement('pubDate', $item->getPubDate());
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endElement();
        $writer->endDocument();

        return $writer->flush();
    }
}
