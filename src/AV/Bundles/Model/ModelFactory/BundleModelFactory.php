<?php
/**
 * User: Andy
 * Date: 04/02/15
 * Time: 20:14
 */

namespace AV\Bundles\Model\ModelFactory;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Model\ModelFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BundleModelFactory extends ModelFactory
{
    private $bundleManager;

    public function __construct($queryBuilder, EventDispatcherInterface $eventDispatcher, BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;

        parent::__construct($queryBuilder, $eventDispatcher);
    }

    public function create($modelClass)
    {
        if (strpos($modelClass, ':') !== false) {
            list($bundle, $model) = explode(':', $modelClass);

            $namespace = $this->bundleManager->getBundleConfig($bundle)['namespace'];

            $modelClass = $namespace.'\\Model\\'.$model;
        }

        return parent::create($modelClass);
    }
}
