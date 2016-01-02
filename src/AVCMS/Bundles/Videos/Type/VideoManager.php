<?php
/**
 * User: Andy
 * Date: 09/11/2015
 * Time: 11:36
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Videos;

class VideoManager
{
    /**
     * @var AbstractVideoType[]
     */
    private $importers = [];

    /**
     * @var Videos
     */
    private $videos;

    public function __construct(Videos $videos)
    {
        $this->videos = $videos;
    }

    /**
     * Add a video importer to the manager
     *
     * @param AbstractVideoType $videoImporter
     */
    public function addType(AbstractVideoType $videoImporter)
    {
        $this->importers[$videoImporter->getId()] = $videoImporter;
    }

    /**
     * Get the details for a video from a URL
     *
     * @param $url
     * @return \AV\Model\Entity|mixed
     * @throws \Exception
     */
    public function getVideoDetails($url)
    {
        $importer = $this->getImporterForUrl($url);

        $video = $this->videos->newEntity();

        $importer->getVideoAtUrl($url, $video);

        return $video;
    }

    public function getImporterForUrl($url)
    {
        foreach ($this->importers as $importer) {
            if ($importer->canHandleUrl($url)) {
                return $importer;
            }
        }

        throw new \Exception('No importer found');
    }

    public function getType($id)
    {
        return isset($this->importers[$id]) ? $this->importers[$id] : null;
    }
}
