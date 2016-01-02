<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 12:18
 */

namespace AVCMS\Bundles\Tags\Taxonomy;

use AV\Model\Model;
use AV\Model\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Taxonomy\Model\TaxonomyModel;
use AVCMS\Core\Taxonomy\TaxonomyInterface;

/**
 * Class TagsTaxonomy
 * @package AVCMS\Bundles\Tags\Taxonomy
 *
 * Add the ability to assign tags to content
 */
class TagsTaxonomy implements TaxonomyInterface
{
    /**
     * @var string The column that this taxonomy is identified by, for example when entered into a form
     */
    protected $relationColumn = 'name';

    /**
     * @var \AV\Model\Model
     */
    protected $tags;

    /**
     * @var \AVCMS\Core\Taxonomy\Model\TaxonomyModel
     */
    protected $relationsModel;

    /**
     * @param Model $tagsModel The main model for the taxonomy that holds information about each tag
     * @param TaxonomyModel $relationsModel The model that provides the relational data linking the tag to the content
     */
    public function __construct(Model $tagsModel, TaxonomyModel $relationsModel)
    {
        $this->relationsModel = $relationsModel;
        $this->tags = $tagsModel;
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
        // Add tag with dashes replaced with spaces as a valid matching tag
        foreach ($tags as $tag) {
            $tags[] = str_replace('-', ' ', $tag);
        }

        $query->join($this->relationsModel->getTable(), 'content_id', '=', $model->getTable().'.id', 'left')
            ->join($this->tags->getTable(), $this->tags->getTable().'.id', '=', $this->relationsModel->getTable().'.taxonomy_id', 'left')
            ->where('content_type', $model->getSingular())
            ->whereIn($this->tags->getTable().'.name', $tags);

        return $query;
    }

    /**
     * Updates the tags assigned to a piece of content. Creates new tags as necessary and
     * assigns them (and existing tags) to content using the relations table
     *
     * @param $contentId
     * @param $contentType
     * @param array $tags
     */
    public function update($contentId, $contentType, array $tags)
    {
        if (empty($tags)) {
            $this->relationsModel->deleteContentTaxonomy($contentId, $contentType);
            return;
        }

        $existingTags = $this->tags->query()->whereIn($this->relationColumn, $tags)->get();

        // Make sure it's case-sensitive as the database query is be case-insensitive
        foreach ($existingTags as $index => $existingTag) {
            if (!in_array($existingTag->getName(), $tags)) {
                unset($existingTags[$index]);
            }
        }

        $onlyNewTags = $tags;

        /**
         * @var $existingTag \AVCMS\Bundles\Tags\Model\Tag
         */
        foreach ($existingTags as $existingTag) {
            if(($key = array_search($existingTag->getName(), $onlyNewTags)) !== false) {
                unset($onlyNewTags[$key]);
            }
        }

        $newTags = array();

        foreach ($onlyNewTags as $newTag) {
            if (!in_array($newTag, $existingTags)) {
                $tagEntity = $this->tags->newEntity();
                $tagEntity->setName($newTag);

                $newTags[] = $tagEntity;
            }
        }

        $existingTagIds = array();
        foreach($existingTags as $existingTag) {
            $existingTagIds[] = $existingTag->getId();
        }

        if ($newTags) {
            $newTagIds = $this->tags->insert($newTags);
        }
        else {
            $newTagIds = array();
        }

        $tagIds = array_merge($existingTagIds, $newTagIds);

        $this->relationsModel->deleteContentTaxonomy($contentId, $contentType);
        $this->relationsModel->addContentTaxonomy($contentId, $contentType, $tagIds);

    }

    /**
     * Delete the tags that are assigned to a piece of content
     *
     * @param $contentId
     * @param $contentType
     */
    public function delete($contentId, $contentType)
    {
        $this->relationsModel->deleteContentTaxonomy($contentId, $contentType);
    }

    /**
     * Get the tags that are assigned to a piece of content
     *
     * @param $contentType
     * @param $contentId
     * @return mixed
     */
    public function get($contentId, $contentType)
    {
        return $this->tags->query()->join($this->relationsModel->getTable(), 'taxonomy_id', '=', $this->tags->getTable().'.id', 'left')
            ->where('content_type', $contentType)
            ->where('content_id', $contentId)
            ->get();
    }

    /**
     *  Get the tags that are assigned to an entity and assign them to
     *  that entity
     *
     * @param $entity
     * @param $contentType
     * @throws \Exception
     */
    public function assign($entity, $contentType)
    {
        if (!is_callable(array($entity, 'getId'))) {
            throw new \Exception('Cannot assign taxonomy to an entity that doesn\'t have a getId method');
        }

        $entity->tags = $this->get($entity->getId(), $contentType);
    }

    /**
     * @return TaxonomyModel
     */
    public function getRelationsModel()
    {
        return $this->relationsModel;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->tags;
    }
}
