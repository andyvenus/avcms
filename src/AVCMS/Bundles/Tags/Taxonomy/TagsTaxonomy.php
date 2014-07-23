<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 12:18
 */

namespace AVCMS\Bundles\Tags\Taxonomy;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Model\Model;
use AVCMS\Core\Taxonomy\Model\TaxonomyModel;
use AVCMS\Core\Taxonomy\Taxonomy;

/**
 * Class TagsTaxonomy
 * @package AVCMS\Bundles\Tags\Taxonomy
 *
 * Add the ability to assign tags to content
 */
class TagsTaxonomy implements Taxonomy
{
    /**
     * @var string The column that this taxonomy is identified by, for example when entered into a form
     */
    protected $relation_column = 'name';

    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $tags;

    /**
     * @var \AVCMS\Core\Taxonomy\Model\TaxonomyModel
     */
    protected $relations_model;

    /**
     * @param Model $tags_model The main model for the taxonomy that holds information about each tag
     * @param TaxonomyModel $relations_model The model that provides the relational data linking the tag to the content
     */
    public function __construct(Model $tags_model, TaxonomyModel $relations_model)
    {
        $this->relations_model = $relations_model;
        $this->tags = $tags_model;
    }

    /**
     * Modify a given query to limit it to an array of matched tags
     *
     * @param $model
     * @param QueryBuilderHandler $query
     * @param array $tags
     * @return QueryBuilderHandler
     */
    public function setTaxonomyJoin($model, QueryBuilderHandler $query, array $tags)
    {
        $query->join($this->relations_model->getTable(), 'content_id', '=', $model->getTable().'.id', 'left')
            ->join($this->tags->getTable(), $this->tags->getTable().'.id', '=', $this->relations_model->getTable().'.taxonomy_id', 'left')
            ->where('content_type', $model->getSingular())
            ->whereIn($this->tags->getTable().'.name', $tags);

        return $query;
    }

    /**
     * Updates the tags assigned to a piece of content. Creates new tags as necessary and
     * assigns them (and existing tags) to content using the relations table
     *
     * @param $content_id
     * @param $content_type
     * @param array $tags
     */
    public function update($content_id, $content_type, array $tags)
    {
        $existing_tags = $this->tags->query()->whereIn($this->relation_column, $tags)->get();

        $only_new_tags = $tags;

        /**
         * @var $existing_tag \AVCMS\Bundles\Tags\Model\Tag
         */
        foreach ($existing_tags as $existing_tag) {
            if(($key = array_search($existing_tag->getName(), $only_new_tags)) !== false) {
                unset($only_new_tags[$key]);
            }
        }

        $new_tags = array();

        foreach ($only_new_tags as $new_tag) {
            if (!in_array($new_tag, $existing_tags)) {
                $tag_entity = $this->tags->newEntity();
                $tag_entity->setName($new_tag);

                $new_tags[] = $tag_entity;
            }
        }

        $existing_tag_ids = array();
        foreach($existing_tags as $existing_tag) {
            $existing_tag_ids[] = $existing_tag->getId();
        }

        if ($new_tags) {
            $new_tag_ids = $this->tags->insert($new_tags);
        }
        else {
            $new_tag_ids = array();
        }

        $tag_ids = array_merge($existing_tag_ids, $new_tag_ids);

        $this->relations_model->deleteContentTaxonomy($content_id, $content_type);
        $this->relations_model->addContentTaxonomy($content_id, $content_type, $tag_ids);

    }

    /**
     * Get the tags that are assigned to a piece of content
     *
     * @param $content_type
     * @param $content_id
     * @return mixed
     */
    public function get($content_id, $content_type)
    {
        return $this->tags->query()->join($this->relations_model->getTable(), 'taxonomy_id', '=', $this->tags->getTable().'.id', 'left')
            ->where('content_type', $content_type)
            ->where('content_id', $content_id)
            ->get();
    }

    /**
     *  Get the tags that are assigned to an entity and assign them to
     *  that entity
     *
     * @param $entity
     * @param $content_type
     * @throws \Exception
     */
    public function assign($entity, $content_type)
    {
        if (!is_callable(array($entity, 'getId'))) {
            throw new \Exception('Cannot assign taxonomy to an entity that doesn\'t have a getId methid');
        }

        $entity->tags = $this->get($entity->getId(), $content_type);
    }

    /**
     * @return TaxonomyModel
     */
    public function getRelationsModel()
    {
        return $this->relations_model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->tags;
    }
}