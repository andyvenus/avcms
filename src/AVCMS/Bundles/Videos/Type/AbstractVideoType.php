<?php
/**
 * User: Andy
 * Date: 09/11/2015
 * Time: 11:01
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;
use Curl\Curl;
use Goutte\Client;

abstract class AbstractVideoType
{
    public function __construct()
    {

    }

    abstract public function canHandleUrl($url);

    abstract public function getId();

    abstract public function getName();

    abstract public function getExampleUrl();

    abstract public function getIdFromUrl($url);

    abstract public function getVideoAtUrl($url, Video $video);

    protected function downloadFile($url)
    {
        // blah
    }

    protected function parseJsonAtUrl($url)
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, true);

        $curl->setJsonDecoder(function($response) {
            $json_obj = json_decode($response, true);
            if (!($json_obj === null)) {
                $response = $json_obj;
            }
            return $response;
        });

        $response = $curl->get($url);

        return $response;
    }

    protected function parseHtmlAtUrl($url)
    {
        $client = new Client();

        return $client->request('GET', $url);
    }

    protected function parseCommonHtmlAtUrl($url, $video)
    {
        $crawler = $this->parseHtmlAtUrl($url);

        $data['name'] = htmlspecialchars_decode($crawler->filter('meta[property="og:title"]')->first()->attr('content'));
        $data['description'] = htmlspecialchars_decode($crawler->filter('meta[property="og:description"]')->first()->attr('content'));
        $data['thumbnail'] = htmlspecialchars_decode($crawler->filter('meta[property="og:image"]')->first()->attr('content'));

        try {
            $data['duration'] = $this->secondsToTimestamp($crawler->filter('meta[property="video:duration"]')->first()->attr('content'));
        } catch (\InvalidArgumentException $e) {}

        try {
            $data['tags'] = htmlspecialchars_decode($crawler->filter('meta[name="keywords"]')->first()->attr('content'));
        } catch (\InvalidArgumentException $e) {}


        $video->fromArray($data);

        return $crawler;
    }

    protected function secondsToTimestamp($seconds)
    {
        $timestamp = gmdate('H:i:s', $seconds);

        if (strpos($timestamp, '00:') === 0) {
            $timestamp = substr($timestamp, 3);
        }

        return $timestamp;
    }

    public function extractIdFromUrl($url, $type = 'number')
    {
        $urlParts = explode('/', $url);

        if ($type == 'last') {
            return array_pop($urlParts);
        }

        if (is_numeric($type)) {
            return isset($urlParts[$type]) ? $urlParts[$type] : null;
        }

        $id = null;

        foreach ($urlParts as $urlPart) {
            if ($type == 'number' && is_numeric($urlPart)) {
                $id = $urlPart;
            }
        }

        return $id;
    }

    protected function getQueryParameter($url, $parameter)
    {
        $params = $this->getQueryParameters($url);

        return isset($params[$parameter]) ? $params[$parameter] : false;
    }

    protected function getQueryParameters($url)
    {
        $parts = parse_url($url);

        parse_str($parts['query'], $query);

        return $query;
    }
}
