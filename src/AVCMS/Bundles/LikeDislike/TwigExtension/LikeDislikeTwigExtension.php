<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 21:33
 */

namespace AVCMS\Bundles\LikeDislike\TwigExtension;

use AVCMS\Bundles\LikeDislike\RatingsManager\RateInterface;
use AVCMS\Bundles\LikeDislike\RatingsManager\RatingsManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LikeDislikeTwigExtension extends \Twig_Extension
{
    /**
     * @var RatingsManager
     */
    private $ratingsManager;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $user;

    public function __construct(RatingsManager $ratingsManager, TokenStorage $tokenStorage)
    {
        $this->ratingsManager = $ratingsManager;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'like_dislike_buttons' => new \Twig_SimpleFunction('like_dislike_buttons', array($this, 'likeDislikeButtons'), array('is_safe' => array('html'))),
        );
    }

    public function likeDislikeButtons($contentType, $contentId, RateInterface $content = null, $buttonsTemplate = '@LikeDislike/ratings_buttons.twig')
    {
        $userId = $this->user->getId();

        $rating = $this->ratingsManager->getUsersRating($contentType, $contentId, $userId);

        $ratingValue = null;
        if ($rating !== null) {
            $ratingValue = $rating->getRating();
        }

        return $this->environment->render($buttonsTemplate, ['content_type' => $contentType, 'content_id' => $contentId, 'rating_value' => $ratingValue, 'content' => $content]);
    }

    public function getName()
    {
        return 'avmcs_like_dislike';
    }
}
