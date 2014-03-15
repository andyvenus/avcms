<?php
/**
 * User: Andy
 * Date: 04/03/2014
 * Time: 14:26
 */

namespace AVCMS\Core\Database\Events;

use AVCMS\Core\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class QueryBuilderModelJoinEvent extends Event
{
    /**
     * @var \AVCMS\Core\Model\Model
     */
    private $join_model;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var string
     */
    private $type;

    public function __construct(Model $join_model, array $columns, $type)
    {
        $this->join_model = $join_model;
        $this->columns = $columns;
        $this->type = $type;
    }

    /**
     * @param array $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param Model $join_model
     */
    public function setJoinModel($join_model)
    {
        $this->join_model = $join_model;
    }

    /**
     * @return Model
     */
    public function getJoinModel()
    {
        return $this->join_model;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
} 