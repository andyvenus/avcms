<?php
/**
 * User: Andy
 * Date: 09/11/2015
 * Time: 11:02
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\HttpFoundation\Session\Session;

class YouTubeVideo extends AbstractVideoType
{
    const API_KEY = 'AIzaSyAJhERffb-PlrFpo4o_S6lexAawcbwMcm4';
    const API_URL = 'https://www.googleapis.com/youtube/v3/';

    private $session;

    private $settingsManager;

    public function __construct(Session $session, SettingsManager $settingsManager)
    {
        $this->session = $session;
        $this->settingsManager = $settingsManager;
    }

    public function canHandleUrl($url)
    {
        return strpos($url, 'youtube.com') !== false;
    }

    public function getIdFromUrl($url)
    {
        return $this->getQueryParameter($url, 'v');
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $id = $this->getIdFromUrl($url);

        if (!$id) {
            return false;
        }

        $apiUrl = self::API_URL.'videos?id='.$id.'&key='.self::API_KEY.'&part=snippet,contentDetails';

        $videoInfo = $this->parseJsonAtUrl($apiUrl);

        if (!isset($videoInfo['items'][0])) {
            return false;
        }

        $details = $videoInfo['items'][0];

        $this->assignVideoDetails($video, $details);

        return true;
    }

    public function getVideosById($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $apiUrl = self::API_URL . 'videos?id=' . implode(',', $ids) . '&key=' . self::API_KEY . '&part=snippet,contentDetails';

        $videoInfo = $this->parseJsonAtUrl($apiUrl);

        if (!isset($videoInfo['items'][0])) {
            return [];
        }

        $videos = [];

        foreach ($videoInfo['items'] as $details) {
            $video = new Video();
            $this->assignVideoDetails($video, $details);

            $videos[] = $video;
        }

        return $videos;
    }

    public function searchVideos($filters, $page)
    {
        // No language filter
        if (isset($filters['relevanceLanguage']) && $filters['relevanceLanguage'] == 0) {
            unset($filters['relevanceLanguage']);
        }

        $params = http_build_query($filters);

        $apiUrl = self::API_URL.'search?'.$params.'&part=snippet&key='.self::API_KEY.'&maxResults=50&videoEmbeddable=true&type=video';

        if ($page != 1 && $this->session->has('youtube_api_next_page')) {
            $apiUrl .= '&pageToken='.$this->session->get('youtube_api_next_page');
        }

        $videosInfo = $this->parseJsonAtUrl($apiUrl);

        if (isset($videosInfo['nextPageToken'])) {
            $this->session->set('youtube_api_next_page', $videosInfo['nextPageToken']);
        }

        if (!isset($videosInfo['items'])) {
            return [];
        }

        $videos = [];

        foreach ($videosInfo['items'] as $videoInfo) {
            $video = new Video();
            $this->assignVideoDetails($video, $videoInfo);

            $videos[] = $video;
        }

        return $videos;
    }

    private function assignVideoDetails(Video $video, $details)
    {
        if (isset($details['id']['videoId'])) {
            $id = $details['id']['videoId'];
        } elseif (!is_array($details['id'])) {
            $id = $details['id'];
        } else {
            return;
        }

        $video->setFile('https://www.youtube.com/watch?v='.$id);

        $video->setDescription($details['snippet']['description']);
        $video->setName($details['snippet']['title']);

        if ($this->settingsManager->getSetting('videos_large_thumbnails') && isset($details['snippet']['thumbnails']['maxres']['url'])) {
            $video->setThumbnail($details['snippet']['thumbnails']['maxres']['url']);
        } else {
            $video->setThumbnail($details['snippet']['thumbnails']['medium']['url']);
        }

        $video->setProviderId($id);
        $video->setProvider($this->getId());

        if (isset($details['contentDetails'])) {
            $video->setDuration($this->getDuration($details['contentDetails']['duration']));
        }
    }

    public function getId()
    {
        return 'youtube';
    }

    public function getName()
    {
        return 'YouTube';
    }

    public function getExampleUrl()
    {
        return 'https://www.youtube.com/watch?v=[video_id]';
    }

    private function getDuration($youtubeDuration){
        $start = new \DateTime('@0');
        $start->add(new \DateInterval($youtubeDuration));

        $formatted = $start->format('H:i:s');

        if (strpos($formatted, '00:') === 0) {
            $formatted = substr($formatted, 3);
        }

        return $formatted;
    }
}
