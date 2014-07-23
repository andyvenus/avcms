<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:00
 */

namespace AVCMS\Core\Taxonomy;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Model\Model;

/**
 * Class TaxonomyManager
 * @package AVCMS\Core\Taxonomy
 *
 * Manages the different taxonomies that can be assigned to content
 */
class TaxonomyManager
{
    /**
     * @var Taxonomy[]
     */
    protected $taxonomies = array();

    /**
     * @param $name
     * @param Taxonomy $taxonomy
     */
    public function addTaxonomy($name, Taxonomy $taxonomy)
    {
        $this->taxonomies[$name] = $taxonomy;
    }

    /**
     * @param $name
     * @throws \Exception
     * @return Taxonomy
     */
    public function getTaxonomy($name)
    {
        if (!isset($this->taxonomies[$name])) {
            throw new \Exception("Taxonomy $name does not exist");
        }

        return $this->taxonomies[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasTaxonomy($name)
    {
        return isset($this->taxonomies[$name]);
    }

    /**
     * Modify a given query to select content matching an array of taxonomy values
     *
     * @param $taxonomy_name
     * @param Model $model The model that provided the original query
     * @param QueryBuilderHandler $query
     * @param array $values
     */
    public function setTaxonomyJoin($taxonomy_name, Model $model, QueryBuilderHandler $query, array $values)
    {
        $this->getTaxonomy($taxonomy_name)->setTaxonomyJoin($model, $query, $values);
    }

    /**
     * Save the taxonomy belonging to a content type
     *
     * @param $taxonomy
     * @param $content_id
     * @param $content_type
     * @param $values
     */
    public function update($taxonomy, $content_id, $content_type, $values)
    {
        $this->getTaxonomy($taxonomy)->update($content_id, $content_type, $values);
    }

    /**
     *  Get the tags that are assigned to an entity and assign them to
     *  that entity
     *
     * @param $taxonomy
     * @param $entity
     * @param $content_type
     * @throws \Exception
     */
    public function assign($taxonomy, $entity, $content_type)
    {
        $this->getTaxonomy($taxonomy)->assign($entity, $content_type);
    }

    /**
     * Get the taxonomy assigned to a piece of content
     *
     * @param $taxonomy
     * @param $content_id
     * @param $content_type
     * @return mixed
     */
    public function get($taxonomy, $content_id, $content_type)
    {
        return $this->getTaxonomy($taxonomy)->get($content_id, $content_type);
    }
} 