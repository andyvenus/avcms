<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:00
 */

namespace AVCMS\Core\Taxonomy;

use AV\Model\Model;
use AV\Model\QueryBuilder\QueryBuilderHandler;

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
     * @param $taxonomyName
     * @param Model $model The model that provided the original query
     * @param QueryBuilderHandler $query
     * @param array $values
     */
    public function setTaxonomyJoin($taxonomyName, Model $model, QueryBuilderHandler $query, array $values)
    {
        $this->getTaxonomy($taxonomyName)->setTaxonomyJoin($model, $query, $values);
    }

    /**
     * Save the taxonomy belonging to a content type
     *
     * @param $taxonomy
     * @param $contentId
     * @param $contentType
     * @param $values
     */
    public function update($taxonomy, $contentId, $contentType, $values)
    {
        $this->getTaxonomy($taxonomy)->update($contentId, $contentType, $values);
    }

    /**
     *  Get the tags that are assigned to an entity and assign them to
     *  that entity
     *
     * @param $taxonomy
     * @param $entity
     * @param $contentType
     * @throws \Exception
     */
    public function assign($taxonomy, $entity, $contentType)
    {
        $this->getTaxonomy($taxonomy)->assign($entity, $contentType);
    }

    /**
     * Get the taxonomy assigned to a piece of content
     *
     * @param $taxonomy
     * @param $contentId
     * @param $contentType
     * @return mixed
     */
    public function get($taxonomy, $contentId, $contentType)
    {
        return $this->getTaxonomy($taxonomy)->get($contentId, $contentType);
    }
} 