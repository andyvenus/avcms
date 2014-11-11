<?php
/**
 * User: Andy
 * Date: 11/11/14
 * Time: 11:52
 */

namespace AVCMS\Bundles\Tags\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagSuggestionsController extends Controller
{
    public function tagSuggestionsAction()
    {
        $tagsModel = $this->model('TagsModel');

        $tags = $tagsModel->newest(100);

        $tagList = [];
        foreach ($tags as $tag) {
            $tagList[] = $tag->getName();
        }

        return new JsonResponse($tagList);
    }
} 