<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:01
 */

namespace AVCMS\Core\Taxonomy;

use AV\Model\QueryBuilder\QueryBuilderHandler;

interface TaxonomyInterface {
    public function getModel();

    public function get($contentId, $contentType);

    public function getRelationsModel();

    public function update($contentId, $contentType, array $tags);

    public function setTaxonomyJoin($model, QueryBuilderHandler $query, array $tags);

    public function assign($entity, $contentType);
}