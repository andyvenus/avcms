<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 20:21
 */

namespace AVCMS\Bundles\LikeDislike\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LikeDislikeController extends Controller
{
    private $ratings;

    /**
     * @var \AVCMS\Bundles\LikeDislike\RatingsManager\RatingsManager
     */
    private $ratingsManager;

    public function setUp()
    {
        $this->ratings = $this->model('Ratings');
        $this->ratingsManager = $this->container->get('ratings_manager');
    }

    public function registerVoteAction(Request $request)
    {
        $rating = $request->get('rating');
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        if ($rating === null || $contentType === null || $contentId === null || !$this->ratingsManager->contentTypeValid($contentType)) {
            throw $this->createNotFoundException('Rating type not found');
        }

        $result = $this->ratingsManager->registerRating($rating, $contentType, $contentId);

        return new JsonResponse(['success' => $result]);
    }
}
