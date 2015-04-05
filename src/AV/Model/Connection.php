<?php

namespace AV\Model;

use AV\Model\Exception\DatabaseConfigMissingException;
use Pixie\AliasFacade;
use Pixie\Connection as PixieConnection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Viocon\Container;

class Connection extends PixieConnection {

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct($adapter, $adapterConfig, $alias = null, Container $container = null, EventDispatcherInterface $eventDispatcher = null)
    {
        if (is_string($adapterConfig)) {
            if (file_exists($adapterConfig)) {
                $adapterConfig = include($adapterConfig);
            }
            else {
                throw new DatabaseConfigMissingException();
            }
        }

        parent::__construct($adapter, $adapterConfig, $alias, $container);

        $this->eventDispatcher = $eventDispatcher;
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
        $builder = $this->container->build('\AV\Model\QueryBuilder\QueryBuilderHandler', array($this));
        AliasFacade::setQueryBuilderInstance($builder);
    }

    /**
     * Returns an instance of Query Builder
     *
     * Replaces default with AVCMS class
     */
    public function getQueryBuilder()
    {
        return $this->container->build('\AV\Model\QueryBuilder\QueryBuilderHandler', array($this));
    }

    /**
     * @return null|EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

}
