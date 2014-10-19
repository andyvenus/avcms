<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 16:06
 */

namespace AV\Model\Tests;


use AV\Model\ModelFactory;

abstract class ModelTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    static private $shared_model_factory = null;

    static private $shared_query_builder = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $conn = null;

    /**
     * @var \AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler
     */
    protected $query_builder = null;

    /**
     * @var \AV\Model\ModelFactory
     */
    protected $model_factory = null;

    public function setUp()
    {
        parent::setUp();

    }

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = $this->newPDO();
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        if ($this->query_builder == null || $this->model_factory == null) {
            if (self::$shared_query_builder == null || self::$shared_model_factory == null) {
                $dbconfig = array(
                    'driver'    => 'mysql', // Db driver
                    'host'      => $GLOBALS['DB_HOST'],
                    'database'  => $GLOBALS['DB_DBNAME'],
                    'username'  => $GLOBALS['DB_USER'],
                    'password'  => $GLOBALS['DB_PASSWD'],
                    'charset'   => 'utf8', // Optional
                    'collation' => 'utf8_unicode_ci', // Optional
                );

                self::$shared_query_builder = new \AVCMS\Core\Database\Connection('mysql', $dbconfig, 'QB');

                $mock_dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');

                self::$shared_model_factory = new ModelFactory(self::$shared_query_builder->getQueryBuilder(), $mock_dispatcher);
            }

            $this->query_builder = self::$shared_query_builder;
            $this->model_factory = self::$shared_model_factory;
        }

        return $this->conn;
    }

    final private function newPDO()
    {
        return new \PDO( "mysql:dbname={$GLOBALS['DB_DBNAME']};host={$GLOBALS['DB_HOST']}", $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
    }
}