<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 21:33
 */

namespace AVCMS\Bundles\LikeDislike\TwigExtension;

use AVCMS\Bundles\LikeDislike\RatingsManager\RateInterface;
use AVCMS\Bundles\LikeDislike\RatingsManager\RatingsManager;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;
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

    private $tokenStorage;

    private $settingsManager;

    private $requestStack;

    public function __construct(RatingsManager $ratingsManager, TokenStorage $tokenStorage, SettingsManager $settingsManager, RequestStack $requestStack)
    {
        $this->ratingsManager = $ratingsManager;
        $this->tokenStorage = $tokenStorage;
        $this->settingsManager = $settingsManager;
        $this->requestStack = $requestStack;
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
        if (!$this->settingsManager->getSetting('enable_ratings')) {
            return null;
        }

        $userId = $this->getUser()->getId();

        if ($userId) {
            $rating = $this->ratingsManager->getUsersRating($contentType, $contentId, $userId);
        } else {
            $ip = $this->requestStack->getCurrentRequest()->getClientIp();
            $rating = $this->ratingsManager->getIpRating($contentType, $contentId, $ip);
        }

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

    protected function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
