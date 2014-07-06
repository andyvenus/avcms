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

    public function addContentTaxonomy($content_id, $content_type, $values)
    {
        if (empty($values)) return;

        foreach ($values as $value) {
            $db_values[] = array(
                'content_id' => $content_id,
                'content_type' => $content_type,
                'taxonomy_id' => $value
            );
        }

        $this->query()->where('content_id', $content_id)->where('content_type', $content_type)->insert($db_values);
    }

    public function deleteContentTaxonomy($content_id, $content_type)
    {
        $this->query()->where('content_id', $content_id)->where('content_type', $content_type)->delete();
    }

    public function getEntity()
    {
        return null;
    }
}