<?php
/**
 * User: Andy
 * Date: 23/06/2014
 * Time: 19:10
 */

namespace AVCMS\Core\Taxonomy\Model;

use AVCMS\Core\Model\Model;

abstract class TaxonomyModel extends Model
{
    public function getContentIds($type, $values)
    {
        $ids = $this->query()->whereIn('slug', $values)->where('content_type', $type)->getColumn('id');

        return $ids;
    }

    public function addContentTaxonomy($contentId, $contentType, $values)
    {
        if (empty($values)) return;

        foreach ($values as $value) {
            $dbValues[] = array(
                'content_id' => $contentId,
                'content_type' => $contentType,
                'taxonomy_id' => $value
            );
        }

        if (isset($dbValues)) {
            $this->query()->where('content_id', $contentId)->where('content_type', $contentType)->insert($dbValues);
        }
    }

    public function deleteContentTaxonomy($contentId, $contentType)
    {
        $this->query()->where('content_id', $contentId)->where('content_type', $contentType)->delete();
    }

    public function getEntity()
    {
        return null;
    }
}