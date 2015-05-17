<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 13:50
 */

namespace AVCMS\Bundles\Games\GameFeeds\ResponseHandler;

class JsonResponseHandler implements ResponseHandlerInterface
{
    private $baseKey;

    public function __construct($baseKey = null)
    {
        $this->baseKey = $baseKey;
    }

    public function getGames($response)
    {
        if (!is_array($response) && !$response instanceof \stdClass) {
            $response = json_decode($response);
        }

        if ($this->baseKey !== null && isset($response->{$this->baseKey})) {
            $response = $response->{$this->baseKey};
        }

        return $response;
    }
}
