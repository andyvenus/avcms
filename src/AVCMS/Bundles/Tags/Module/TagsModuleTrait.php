<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 12:50
 */

namespace AVCMS\Bundles\Tags\Module;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TagsModuleTrait
 * @package AVCMS\Bundles\Tags\Module
 *
 * Works only in controllers
 */
trait TagsModuleTrait
{
    public function getTagsModule($adminSettings, $contentType, $routeName, $routeParam = 'tags')
    {
        $commonTags = $this->model('Tags:TagsTaxonomyModel')->getPopularTags($contentType, $adminSettings['limit']);

        if (!$commonTags) {
            return new Response($this->render('@Tags/module/tags_module.twig'));
        }

        $tags = $this->model('Tags:TagsModel')->query()->whereIn('id', array_keys($commonTags))->orderBy('name')->get();

        $maxSize = 24; // max font size in pixels
        $minSize = 12; // min font size in pixels

        // largest and smallest array values
        $maxQty = max(array_values($commonTags));
        $minQty = min(array_values($commonTags));

        // find the range of values
        $spread = $maxQty - $minQty;
        if ($spread == 0) { // we don't want to divide by zero
            $spread = 1;
        }

        // set the font-size increment
        $step = ($maxSize - $minSize) / ($spread);

        foreach ($commonTags as $tag => $uses) {
            $size = round($minSize + (($uses - $minQty) * $step));
            $commonTags[$tag] = ['uses' => $uses, 'size' => $size];
        }

        foreach ($tags as $tag) {
            $tag->uses = $commonTags[$tag->getId()]['uses'];
            $tag->size = $commonTags[$tag->getId()]['size'];
        }

        return new Response($this->render('@Tags/module/tags_module.twig', [
            'tags' => $tags,
            'route_name' => $routeName,
            'route_param' => $routeParam
        ]));
    }
}
