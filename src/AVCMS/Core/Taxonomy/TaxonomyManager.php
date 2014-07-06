<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:00
 */

namespace AVCMS\Core\Taxonomy;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Model\Model;

class TaxonomyManager
{
    protected $taxonomies = array();

    public function addTaxonomy($name, Taxonomy $taxonomy)
    {
        $this->taxonomies[$name] = $taxonomy;
    }

    public function getTaxonomy($name)
    {
        return $this->taxonomies[$name];
    }

    public function hasTaxonomy($name)
    {
        return isset($this->taxonomies[$name]);
    }

    public function setTaxonomyJoin($taxonomy_name, Model $model, QueryBuilderHandler $query, array $values)
    {
        $this->getTaxonomy($taxonomy_name)->setTaxonomyJoin($model, $query, $values);
    }

    public function update($taxonomy, $content_id, $content_type, $values)
    {
        $this->getTaxonomy($taxonomy)->update($content_id, $content_type, $values);
    }
} 