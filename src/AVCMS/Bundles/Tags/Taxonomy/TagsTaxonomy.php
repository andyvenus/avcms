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

    public function getRelationsModel()
    {
        return $this->relations_model;
    }

    public function getModel()
    {
        return $this->tags;
    }
}