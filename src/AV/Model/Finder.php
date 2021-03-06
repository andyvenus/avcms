<?php
/**
 * User: Andy
 * Date: 22/04/2014
 * Time: 12:45
 */

namespace AV\Model;

use AVCMS\Core\Taxonomy\TaxonomyManager;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Pixie\QueryBuilder\Raw;
use Symfony\Component\HttpFoundation\Request;

class Finder
{
    /**
     * @var \AV\Model\Model
     */
    protected $model;

    /**
     * @var \AV\Model\QueryBuilder\QueryBuilderHandler
     */
    protected $currentQuery;

    /**
     * @var int
     */
    protected $resultsPerPage = 0;

    /**
     * @var array
     */
    protected $searchFields = array();

    /**
     * @var int
     */
    protected $currentPage = 1;

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * @var array
     */
    protected $taxonomies = array();

    /**
     * @var array The parameters that are extracted from the request
     */
    protected $validRequestParameters;

    /**
     * @var array An array of values extracted from the request or the default
     */
    protected $requestFilters = [];

    /**
     * @var TaxonomyManager
     */
    protected $taxonomyManager;

    /**
     * @var array
     */
    protected $sortOptions;

    public function __construct(Model $model, TaxonomyManager $taxonomyManager = null)
    {
        $this->model = $model;
        $this->taxonomyManager = $taxonomyManager;
        $this->currentQuery = $model->query();

        $this->sortOptions = [
            'newest' => ['id DESC'],
            'oldest' => ['id ASC']
        ];
    }

    public function setSortOptions(array $sortOptions)
    {
        $table = $this->model->getTable();
        foreach ($sortOptions as $key => $val) {
            foreach ((array) $val as $sort) {
                if (strpos($sort, '(') === false) {
                    $this->sortOptions[$key][] = $table . '.' . $sort;
                }
                else {
                    $this->sortOptions[$key][] = $sort;
                }
            }
        }
    }

    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }

    public function handleRequest(Request $request, array $filters)
    {
        $validRequestParameters = array();
        foreach ($filters as $filter => $default) {
            $filterMethod = str_replace('_', '', $filter);
            if (method_exists($this, $filterMethod)) {
                $this->$filterMethod($request->get($filter, $default));
            }
            elseif ($this->taxonomyManager && $this->taxonomyManager->hasTaxonomy($filter)) {
                $this->taxonomy($filter, $request->get($filter, $default));
            }
            else {
                throw new \Exception('No filter method found for filter '.$filter);
            }

            $validRequestParameters[] = $filter;
            $this->requestFilters[$filter] = $request->get($filter, $default);
        }

        $this->validRequestParameters = $validRequestParameters;

        return $this;
    }

    public function order($order)
    {
        $orders = (array) $this->getDbSort($order);

        foreach ($orders as $order) {
            $this->doOrder($order);
        }

        return $this;
    }

    private function doOrder($order)
    {
        $orderSplit = explode(' ', $order);

        if ($orderSplit[0] == 'rand()') {
            $orderSplit[0] = $this->model->query()->raw('rand()');
        }

        if (strpos($orderSplit[0], '.') === false && !$orderSplit[0] instanceof Raw) {
            $orderSplit[0] = $this->model->getTable().'.'.$orderSplit[0];
        }

        $this->currentQuery->orderBy($orderSplit[0], $orderSplit[1]);

        return $this;
    }

    public function getDbSort($order)
    {
        if (!isset($this->sortOptions[$order])) {
            return 'id DESC';
        }
        else {
            return $this->sortOptions[$order];
        }
    }

    public function limit($limit)
    {
        $this->currentQuery->limit($limit);

        return $this;
    }

    public function customOrder($field, $type = 'ASC')
    {
        $this->currentQuery->orderBy($field, $type);

        return $this;
    }

    public function page($page)
    {
        if ($this->resultsPerPage === 0) {
            return $this;
        }

        $this->currentPage = $page;

        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }

        $this->currentQuery->paginated($page, $this->resultsPerPage);

        return $this;
    }

    public function search($term)
    {
        if (!$term) {
            return $this;
        }

        $searchFields = $this->searchFields;

        $this->currentQuery->where(function (QueryBuilderHandler $q) use ($term, $searchFields)
        {
            foreach ($searchFields as $searchField) {
                if (!isset($i)) {
                    $func = 'where';
                    $i = true;
                }
                else {
                    $func = 'orWhere';
                }

                $q->$func($searchField, 'LIKE', '%'.$term.'%');
            }
        });

        return $this;
    }

    public function where($key, $operator = null, $value = null)
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->currentQuery->where($key, $operator, $value);

        return $this;
    }

    public function ids($ids, $column = 'id')
    {
        if ($ids !== null) {
            // If there's no ids, we don't want to get anything so random value
            if (empty($ids)) {
                $this->currentQuery->where($column, null);
            }
            else {
                $this->currentQuery->where($column, 'IN', $ids);
            }
        }

        return $this;
    }

    public function setSearchFields(array $fields)
    {
        $this->searchFields = $fields;

        return $this;
    }

    public function published()
    {
        $this->currentQuery->where('published', 1);

        $entity = $this->model->getEntity();

        if (method_exists($entity, 'getPublishDate')) {
            $this->currentQuery->where('publish_date', '<=', time());
        }

        return $this;
    }

    public function taxonomy($taxonomy, $values = null)
    {
        if (!$this->taxonomyManager->hasTaxonomy($taxonomy)) {
            throw new \Exception(sprintf("Taxonomy %s does not exist in the taxonomy manager", $taxonomy));
        }

        if ($values !== null) {
            if (!is_array($values)) {
                $values = explode('|', $values);
            }

            $this->taxonomyManager->setTaxonomyJoin($taxonomy, $this->model, $this->currentQuery, $values);
        }

        return $this;
    }

    public function join(Model $joinModel, array $columns, $type = 'left', $joinTo = null, $key = null, $operator = '=', $value = null, $joinSingular = null)
    {
        $this->currentQuery->modelJoin($joinModel, $columns, $type, $joinTo, $key, $operator, $value, $joinSingular);

        return $this;
    }

    public function joinTaxonomy($taxonomy)
    {
        $this->taxonomies[] = $taxonomy;

        return $this;
    }

    public function getQuery()
    {
        return $this->currentQuery;
    }

    public function id($id = null)
    {
        if ($id !== null)
            $this->currentQuery->where($this->model->getTable().'.id', $id);

        return $this;
    }

    public function slug($slug = null)
    {
        if ($slug !== null)
            $this->currentQuery->where($this->model->getTable().'.slug', $slug);

        return $this;
    }

    public function first()
    {
        $result = $this->currentQuery->first();

        if ($result !== null) {
            $this->assignTaxonomies($result);
        }

        return $result;
    }

    public function get()
    {
        $results = $this->currentQuery->get();

        if ($this->taxonomyManager && !empty($this->taxonomies)) {
            foreach ($results as $entity) {
                $this->assignTaxonomies($entity);
            }
        }

        return $results;
    }

    protected function assignTaxonomies($entity)
    {
        if ($this->taxonomyManager && !empty($this->taxonomies)) {
            foreach ($this->taxonomies as $taxonomy) {
                $this->taxonomyManager->assign($taxonomy, $entity, $this->model->getSingular());
            }
        }
    }

    /**
     * @return null
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        if ($this->resultsPerPage === 0) {
            return 1;
        }

        $totalResults = $this->currentQuery->count();

        return ceil($totalResults / $this->resultsPerPage);
    }

    public function getRequestFilters()
    {
        return $this->requestFilters;
    }
}
