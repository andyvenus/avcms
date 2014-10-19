<?php
/**
 * User: Andy
 * Date: 04/03/2014
 * Time: 14:26
 */

namespace AV\Model\Event;

use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class QueryBuilderModelJoinEvent extends Event
{
    /**
     * @var \AV\Model\Model
     */
    private $joinModel;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var string
     */
    private $type;

    public function __construct(Model $joinModel, array $columns, $type)
    {
        $this->joinModel = $joinModel;
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
     * @param Model $joinModel
     */
    public function setJoinModel($joinModel)
    {
        $this->joinModel = $joinModel;
    }

    /**
     * @return Model
     */
    public function getJoinModel()
    {
        return $this->joinModel;
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