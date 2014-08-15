<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:00
 */

namespace AVCMS\Core\Taxonomy;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TaxonomyManager
 * @package AVCMS\Core\Taxonomy
 *
 * Manages the different taxonomies that can be assigned to content
 */
class ContainerAwareTaxonomyManager extends TaxonomyManager
{
    protected $container;

    public function __construct(ContainerInterface $container)
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