<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 13:50
 */

namespace AVCMS\Bundles\Games\GameFeeds\ResponseHandler;

class JsonResponseHandler implements ResponseHandlerInterface
{
    public function getGames($response)
    {
        if (is_array($response)) {
            return $response;
        }

        return json_decode($response);
    }
}
