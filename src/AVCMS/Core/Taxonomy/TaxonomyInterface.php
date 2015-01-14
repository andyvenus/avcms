<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:01
 */

namespace AVCMS\Core\Taxonomy;

use AV\Model\QueryBuilder\QueryBuilderHandler;

interface TaxonomyInterface
{
    public function get($contentId, $contentType);

    public function getRelationsModel();

    public function update($contentId, $contentType, array $taxonomyValues);

    public function delete($contentId, $contentType);

    public function setTaxonomyJoin($model, QueryBuilderHandler $query, array $taxonomyValues);

    public function assign($entity, $contentType);
}
