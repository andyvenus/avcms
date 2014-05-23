<?php
/**
 * User: Andy
 * Date: 22/04/2014
 * Time: 12:45
 */

namespace AVCMS\Core\Finder;

use AVCMS\Core\Model\Model;
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
     * @var array
     */
    protected $filters = array();

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->sort_options = array(
            'newest' => 'id DESC',
            'oldest' => 'id ASC'
        );
    }

    public function setResultsPerPage($results_per_page)
    {
        $this->results_per_page = $results_per_page;
    }

    public function setJoin(Model $join_model, array $columns, $type = 'left', $join_to = null, $key = null, $operator = '=', $value = null)
    {

    }

    public function handleRequest(Request $request, array $filters)
    {
        foreach ($filters as $filter => $default) {
            if (!method_exists($this, $filter)) {
                throw new \Exception('No filter method found for filter '.$filter);
            }

            $this->filters[$filter][] = $request->get($filter, $default);
        }

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
        if ($page < 1) {
            $page = 1;
        }

        $this->current_query->paginated($page, $this->results_per_page);
    }

    public function search($term)
    {
        if (!$term) {
            return;
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
    }

    public function setSearchFields(array $fields)
    {
        $this->search_fields = $fields;
    }

    public function getQuery($ignored_filters = array())
    {
        $this->current_query = $this->model->query();

        foreach ($this->filters as $filter_name => $filters) {
            if (!in_array($filter_name, $ignored_filters)) {
                foreach ($filters as $filter) {
                    $this->$filter_name($filter);
                }
            }
        }

        return $this->current_query;
    }

    public function getTotalPages()
    {
        $query = $this->getQuery(array('page'));

        return ceil($query->count() / $this->results_per_page);
    }

    public function getCurrentPage()
    {
        if (isset($this->filters['page'][0])) {
            return $this->filters['page'][0];
        }

        return null;
    }
}