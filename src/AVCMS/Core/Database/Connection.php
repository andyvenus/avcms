<?php

namespace AVCMS\Core\Database;

use Pixie\Connection as PixieConnection;

class Connection extends PixieConnection {

    /**
     * Create an easily accessible query builder alias
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
     */
    public function getQueryBuilder()
    {
        return $this->container->build('\\AVCMS\Core\\Database\\QueryBuilder\\QueryBuilderHandler', array($this));
    }

}