<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 20:28
 */

namespace AVCMS\Bundles\LikeDislike\RatingsManager;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Model\ModelFactory;
use AVCMS\Bundles\LikeDislike\Model\Ratings;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RatingsManager
{
    private $bundleManager;

    private $contentTypes = [];

    private $ratings;

    private $modelFactory;

    public function __construct(BundleManagerInterface $bundleManager, Ratings $ratings, TokenStorage $tokenStorage, ModelFactory $modelFactory)
    {
        $this->bundleManager = $bundleManager;
        $this->ratings = $ratings;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->modelFactory = $modelFactory;

        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (isset($bundleConfig['ratings'])) {
                foreach ($bundleConfig['ratings'] as $contentType => $content) {
                    $this->contentTypes[$contentType] = $content;
                }
            }
        }
    }

    public function contentTypeValid($contentType)
    {
        return isset($this->contentTypes[$contentType]);
    }

    public function getContentTypeConfig($contentType)
    {
        return $this->contentTypes[$contentType];
    }

    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    public function registerRating($rating, $contentType, $contentId)
    {
        if (!$this->contentTypeValid($contentType)) {
            throw new \Exception("Content type not valid");
        }

        $contentTypeConfig = $this->getContentTypeConfig($contentType);

        if ($rating === '1' || $rating === 1) {
            $rating = 1;
        }
        elseif ($rating === '0' || $rating === 0) {
            $rating = 0;
        }
        else {
            $rating = null;
        }

        $contentModel = $this->modelFactory->create($contentTypeConfig['model']);
        $content = $contentModel->getOne($contentId);

        if ($content === null) {
            return false;
        }

        $baseQuery = $this->ratings->query()
            ->where('content_id', $contentId)
            ->where('content_type', $contentType)
            ->where('user_id', $this->user->getId());

        $entity = $baseQuery->first();

        if ($entity !== null && $content instanceof RateInterface) {
            if ($entity->getRating() === '1' || $entity->getRating() === 1) {
                $content->setLikes($content->getLikes() - 1);
            }
            elseif ($entity->getRating() === '0' || $entity->getRating() === 0) {
                $content->setDislikes($content->getDislikes() - 1);
            }
        }
        else {
            $entity = $this->ratings->newEntity();
        }

        if ($rating !== null) {
            $entity->setContentId($contentId);
            $entity->setContentType($contentType);
            $entity->setDate(time());
            $entity->setUserId($this->user->getId());
            $entity->setRating($rating);

            $this->ratings->save($entity);
        }
        else {
            $baseQuery->delete();
        }

        if ($content instanceof RateInterface) {
            if ($rating === 1) {
                $content->setLikes($content->getLikes() + 1);
            }
            elseif ($rating === 0) {
                $content->setDislikes($content->getDislikes() + 1);
            }

            $contentModel->save($content);
        }

        return true;
    }

    public function getUsersRating($contentType, $contentId, $userId)
    {
        return $this->ratings->query()->where('content_type', $contentType)->where('content_id', $contentId)->where('user_id', $userId)->first();
    }
}
