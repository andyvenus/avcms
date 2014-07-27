<?php

namespace AVCMS\Core\Database;

use Pixie\AliasFacade;
use Pixie\Connection as PixieConnection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Viocon\Container;

class Connection extends PixieConnection {

    protected $event_dispatcher;

    public function __construct($adapter, array $adapterConfig, $alias = null, Container $container = null, EventDispatcher $event_dispatcher = null)
    {
        parent::__construct($adapter, $adapterConfig, $alias, $container);

        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * Create an easily accessible query builder alias
     *
     * Replaces default with AVCMS class
     *
     * @param $alias
     */
    public function createAlias($alias)
    {
        class_alias('Pixie\\AliasFacade', $alias);
        $builder = $this->container->build('\\AVCMS\Core\\Database\\QueryBuilder\\QueryBuilderHandler', array($this));
        AliasFacade::setQueryBuilderInstance($builder);
    }

    /**
     * Returns an instance of Query Builder
     *
     * Replaces default with AVCMS class
     */
    public function getQueryBuilder()
    {
        return $this->container->build('\\AVCMS\Core\\Database\\QueryBuilder\\QueryBuilderHandler', array($this));
    }

    /**
     * @return null|EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->event_dispatcher;
    }

}