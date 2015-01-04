<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 20:39
 */

namespace AVCMS\Bundles\LikeDislike\RatingsManager;

interface RateInterface
{
    public function setLikes($likes);

    public function getLikes();

    public function setDislikes($dislikes);

    public function getDislikes();
}
