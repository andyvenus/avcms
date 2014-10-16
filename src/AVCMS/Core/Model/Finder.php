<?php
/**
 * User: Andy
 * Date: 22/04/2014
 * Time: 12:45
 */

namespace AVCMS\Core\Model;

use AVCMS\Core\Model\Model;
use AVCMS\Core\Taxonomy\TaxonomyManager;
use Symfony\Component\HttpFoundation\Request;

class Finder
{
    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $model;

    /**
     * @var \AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler
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

    public function __construct(Model $model, TaxonomyManager $taxonomyManager = null)
    {
        $this->model = $model;
        $this->taxonomyManager = $taxonomyManager;
        $this->currentQuery = $model->query();

        $this->sortOptions = array(
            'newest' => 'id DESC',
            'oldest' => 'id ASC'
        );
    }

    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }

    public function setJoin(Model $joinModel, array $columns, $type = 'left', $join_to = null, $key = null, $operator = '=', $value = null)
    {
        //todo this - or delete
    }

    public function handleRequest(Request $request, array $filters)
    {
        $validRequestParameters = array();
        foreach ($filters as $filter => $default) {
            if (method_exists($this, $filter)) {
                $this->$filter($request->get($filter, $default));
            }
            elseif ($this->taxonomyManager && $this->taxonomyManager->hasTaxonomy($filter)) {
                $this->taxonomy($filter, $request->get($filter, $default));
            }
            else {
                throw new \Exception('No filter method found for filter '.$filter);
            }

            $validRequestParameters[] = $filter;
        }

        $this->validRequestParameters = $validRequestParameters;

        return $this;
    }

    public function order($order)
    {
        if (!isset($this->sortOptions[$order])) {
            $order = 'id DESC';
        }
        else {
            $order = $this->sortOptions[$order];
        }

        $orderSplit = explode(' ', $order);

        $this->currentQuery->orderBy($orderSplit[0], $orderSplit[1]);

        return $this;
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

        $search_fields = $this->searchFields;

        $this->currentQuery->where(function ($q) use ($term, $search_fields)
        {
            foreach ($search_fields as $search_field) {
                if (!isset($i)) {
                    $func = 'where';
                    $i = true;
                }
                else {
                    $func = 'orWhere';
                }

                //todo: is secure?
                $q->$func($search_field, 'LIKE', '%'.$term.'%');
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

    public function setSearchFields(array $fields)
    {
        $this->searchFields = $fields;

        return $this;
    }

    // todo: time-based published
    public function published()
    {
        $this->currentQuery->where('published', 1);

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

    public function join(Model $joinModel, array $columns, $type = 'left', $joinTo = null, $key = null, $operator = '=', $value = null)
    {
        $this->currentQuery->modelJoin($joinModel, $columns, $type, $joinTo, $key, $operator, $value);

        return $this;
    }

    public function joinTaxonomy($taxonomy)
    {
        $this->taxonomies[] = $taxonomy;

        return $this;
    }

    public function getQuery($ignoredFilters = array())
    {
        return $this->currentQuery;
    }

    public function getTotalPages()
    {
        $query = $this->getQuery(array('page'));

        return ceil($query->count() / $this->resultsPerPage);
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

        $this->assignTaxonomies($result);

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
}