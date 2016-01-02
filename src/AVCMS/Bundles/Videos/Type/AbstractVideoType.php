<?php
/**
 * User: Andy
 * Date: 09/11/2015
 * Time: 11:01
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;
use Curl\Curl;

abstract class AbstractVideoType
{
    public function __construct()
    {

    }

    abstract public function canHandleUrl($url);

    abstract public function getId();

    abstract public function getName();

    abstract public function getExampleUrl();

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
