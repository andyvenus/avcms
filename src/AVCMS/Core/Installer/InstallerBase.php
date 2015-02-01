<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:09
 */

namespace AVCMS\Core\Installer;

use Symfony\Component\DependencyInjection\ContainerInterface;

class InstallerBase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \AV\Model\QueryBuilder\QueryBuilderHandler
     */
    protected $queryBuilder;

    /**
     * @var \PDO
     */
    protected $PDO;

    /**
     * @var \AV\Model\ModelFactory
     */
    protected $modelFactory;

    /**
     * @var null|string
     */
    protected $prefix;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->queryBuilder = $container->get('query_builder');
        $this->PDO = $this->queryBuilder->getConnection()->getPdoInstance();
        $this->modelFactory = $container->get('model_factory');
        $this->prefix = $this->queryBuilder->getTablePrefix();
    }

    protected function sql($sql)
    {
        $this->PDO->exec($sql);
    }
} 
