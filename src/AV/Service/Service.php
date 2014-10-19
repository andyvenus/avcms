<?php
/**
 * User: Andy
 * Date: 06/08/2014
 * Time: 19:47
 */

namespace AV\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Service
{
    public function getServices($configuration, ContainerBuilder $container);
} 