<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:01
 */

namespace AVCMS\Core\Taxonomy;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;

interface Taxonomy {
    public function getModel();

    public function get($content_id, $content_type);

    public function getRelationsModel();

    public function update($content_id, $content_type, array $tags);

    public function setTaxonomyJoin($model, QueryBuilderHandler $query, array $tags);

    public function assign($entity, $content_type);
} 