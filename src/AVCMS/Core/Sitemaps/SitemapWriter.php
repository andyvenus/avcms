<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 19:43
 */

namespace AVCMS\Core\Sitemaps;

class SitemapWriter
{
    protected $sitemapDirPath;

    protected $sitemapDirUrl;

    public function __construct($sitemapDirUrl, $sitemapDirPath)
    {
        $this->sitemapDirUrl = $sitemapDirUrl;
        $this->sitemapDirPath = $sitemapDirPath;
    }

    /**
     * @param SitemapInterface $sitemap
     */
    public function writeSitemap(SitemapInterface $sitemap)
    {
        if (!file_exists($this->sitemapDirPath.'/'.$sitemap->getId())) {
            mkdir($this->sitemapDirPath.'/'.$sitemap->getId(), 0777, true);
        }

        $page = 1;
        while ($links = $sitemap->getSitemapLinks($page)) {
            $this->writeSitemapFile($links, $sitemap->getId().'/'.$page.'.xml');

            $page++;
        }
    }

    /**
     * @param SitemapLink[] $links
     * @param $filename
     */
    protected function writeSitemapFile(array $links, $filename)
    {
        $writer = new \XMLWriter();
        $writer->openUri($this->sitemapDirPath.'/'.$filename);
        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $writer->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        foreach ($links as $link) {
            $writer->startElement('url');

            $writer->writeElement('loc', $link->getLink());

            if ($link->getLastChanged() !== null) {
                $writer->writeElement('lastmod', $link->getLastChanged()->format('c'));
            }

            if ($images = $link->getImages()) {
                foreach ($images as $image) {
                    $writer->startElement('image:image');
                    $writer->writeElement('image:loc', $image->getUrl());

                    if ($image->getTitle()) {
                        $writer->writeElement('image:title', $image->getTitle());
                    }

                    $writer->endElement();
                }
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }

    /**
     * @param SitemapInterface[] $sitemaps
     */
    public function writeSitemapIndex(array $sitemaps)
    {
        $sitemapFiles = [];

        foreach (new \DirectoryIterator($this->sitemapDirPath) as $sitemapDir) {
            if ($sitemapDir->isDir() && $sitemapDir->isDot() === false && isset($sitemaps[$sitemapDir->getFilename()])) {
                foreach (new \DirectoryIterator($sitemapDir->getPathname()) as $sitemap) {
                    if ($sitemap->isFile() === false || $sitemap->getExtension() !== 'xml') {
                        continue;
                    }

                    $uri = str_replace($this->sitemapDirPath, '', $sitemap->getPathname());
                    $sitemapFiles[] = ['url' => $this->sitemapDirUrl.$uri, 'modified' => $sitemap->getMTime()];
                }
            }
        }

        $writer = new \XMLWriter();
        $writer->openUri($this->sitemapDirPath.'/sitemap.xml');
        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);
        $writer->startElement('sitemapindex');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($sitemapFiles as $sitemap) {
            $writer->startElement('sitemap');

            $writer->writeElement('loc', $sitemap['url']);

            $date = new \DateTime();
            $date->setTimestamp($sitemap['modified']);

            $writer->writeElement('lastmod', $date->format('c'));

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }

    public function getSitemapIndex()
    {
        if (file_exists($this->sitemapDirPath.'/sitemap.xml')) {
            return file_get_contents($this->sitemapDirPath.'/sitemap.xml');
        }

        return null;
    }
}
