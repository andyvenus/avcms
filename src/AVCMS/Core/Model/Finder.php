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
    protected $current_query;

    /**
     * @var int
     */
    protected $results_per_page = 10;

    /**
     * @var array
     */
    protected $search_fields = array();

    /**
     * @var int
     */
    protected $current_page = 1;

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
    protected $valid_request_parameters;

    public function __construct(Model $model, TaxonomyManager $taxonomy_manager = null)
    {
        $this->model = $model;
        $this->taxonomy_manager = $taxonomy_manager;
        $this->current_query = $model->query();

        $this->sort_options = array(
            'newest' => 'id DESC',
            'oldest' => 'id ASC'
        );
    }

    public function setResultsPerPage($results_per_page)
    {
        $this->results_per_page = $results_per_page;

        return $this;
    }

    public function setJoin(Model $join_model, array $columns, $type = 'left', $join_to = null, $key = null, $operator = '=', $value = null)
    {
        //todo this - or delete
    }

    public function handleRequest(Request $request, array $filters)
    {
        $valid_request_parameters = array();
        foreach ($filters as $filter => $default) {
            if (method_exists($this, $filter)) {
                $this->$filter($request->get($filter, $default));
            }
            elseif ($this->taxonomy_manager && $this->taxonomy_manager->hasTaxonomy($filter)) {
                $this->taxonomy($filter, $request->get($filter, $default));
            }
            else {
                throw new \Exception('No filter method found for filter '.$filter);
            }

            $valid_request_parameters[] = $filter;
        }

        $this->valid_request_parameters = $valid_request_parameters;

        return $this;
    }

    public function order($order)
    {
        if (!isset($this->sort_options[$order])) {
            $order = 'id DESC';
        }
        else {
            $order = $this->sort_options[$order];
        }

        $order_split = explode(' ', $order);

        $this->current_query->orderBy($order_split[0], $order_split[1]);

        return $this;
    }

    public function page($page)
    {
        $this->current_page = $page;

        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }

        $this->current_query->paginated($page, $this->results_per_page);

        return $this;
    }

    public function search($term)
    {
        if (!$term) {
            return $this;
        }

        $search_fields = $this->search_fields;

        $this->current_query->where(function ($q) use ($term, $search_fields)
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
        $this->current_query->where($key, $operator, $value);
    }

    public function setSearchFields(array $fields)
    {
        $this->search_fields = $fields;

        return $this;
    }

    // todo: time-based published
    public function published()
    {
        $this->current_query->where('published', 1);

        return $this;
    }

    public function taxonomy($taxonomy, $values = null)
    {
        if (!$this->taxonomy_manager->hasTaxonomy($taxonomy)) {
            throw new \Exception(sprintf("Taxonomy %s does not exist in the taxonomy manager", $taxonomy));
        }

        if ($values !== null) {
            if (!is_array($values)) {
                $values = explode('|', $values);
            }

            $this->taxonomy_manager->setTaxonomyJoin($taxonomy, $this->model, $this->current_query, $values);
        }

        return $this;
    }

    public function join(Model $join_model, array $columns, $type = 'left', $join_to = null, $key = null, $operator = '=', $value = null)
    {
        $this->current_query->modelJoin($join_model, $columns, $type, $join_to, $key, $operator, $value);

        return $this;
    }

    public function joinTaxonomy($taxonomy)
    {
        $this->taxonomies[] = $taxonomy;

        return $this;
    }

    public function getQuery($ignored_filters = array())
    {
        return $this->current_query;
    }

    public function getTotalPages()
    {
        $query = $this->getQuery(array('page'));

        return ceil($query->count() / $this->results_per_page);
    }

    public function id($id = null)
    {
        if ($id !== null)
            $this->current_query->where($this->model->getTable().'.id', $id);

        return $this;
    }

    public function slug($slug = null)
    {
        if ($slug !== null)
            $this->current_query->where($this->model->getTable().'.slug', $slug);

        return $this;
    }

    public function first()
    {
        $result = $this->current_query->first();

        $this->assignTaxonomies($result);

        return $result;
    }

    public function get()
    {
        $results = $this->current_query->get();

        if ($this->taxonomy_manager && !empty($this->taxonomies)) {
            foreach ($results as $entity) {
                $this->assignTaxonomies($entity);
            }
        }

        return $results;
    }

    protected function assignTaxonomies($entity)
    {
        if ($this->taxonomy_manager && !empty($this->taxonomies)) {
            foreach ($this->taxonomies as $taxonomy) {
                $this->taxonomy_manager->assign($taxonomy, $entity, $this->model->getSingular());
            }
        }
    }

    /**
     * @return null
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }
}