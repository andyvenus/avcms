<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 14:05
 */

namespace AVCMS\Bundles\Tags\Model;

use AVCMS\Core\Taxonomy\Model\TaxonomyModel;

class TagsTaxonomyModel extends TaxonomyModel
{
    public function getTable()
    {
        return 'tag_taxonomy';
    }

    public function getSingular()
    {
        return 'tag_taxonomy';
    }

    public function getPopularTags($contentType, $limit)
    {
        $tagInfo = $this->query()
            ->groupBy('taxonomy_id')
            ->orderBy('tag_occurrence', 'DESC')
            ->limit($limit)
            ->where('content_type', $contentType)
            ->select(['taxonomy_id', $this->query()->raw('COUNT(taxonomy_id) AS tag_occurrence')])
            ->get();

        $popularTags = [];
        foreach ($tagInfo as $tag) {
            if ($tag->tag_occurrence > 0) {
                $popularTags[$tag->taxonomy_id] = $tag->tag_occurrence;
            }
        }

        return $popularTags;
    }
}
