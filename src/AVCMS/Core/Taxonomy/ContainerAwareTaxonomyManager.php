<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:00
 */

namespace AVCMS\Core\Taxonomy;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Model\Model;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TaxonomyManager
 * @package AVCMS\Core\Taxonomy
 *
 * Manages the different taxonomies that can be assigned to content
 */
class ContainerAwareTaxonomyManager extends TaxonomyManager
{
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function addContainerTaxonomy($name, $service)
    {
        $this->taxonomies[$name] = $service;
    }

    public function getTaxonomy($name)
    {
        if (isset($this->taxonomies[$name]) && !is_object($this->taxonomies[$name])) {
            $this->taxonomies[$name] = $this->container->get($this->taxonomies[$name]);
        }

        return parent::getTaxonomy($name);
    }
}