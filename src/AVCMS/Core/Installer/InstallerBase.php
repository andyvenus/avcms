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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->queryBuilder = $container->get('query_builder');
        $this->PDO = $this->queryBuilder->getConnection()->getPdoInstance();
        $this->prefix = $this->queryBuilder->getTablePrefix();
    }

    protected function sql($sql)
    {
        $this->PDO->exec($sql);
    }
} 