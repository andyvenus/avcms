<?php
/**
 * User: Andy
 * Date: 05/11/14
 * Time: 11:02
 */

namespace AVCMS\Bundles\Reports\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ReportsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('report_types_manager', 'AVCMS\Bundles\Reports\ReportTypesManager')
            ->setArguments([new Reference('bundle_manager')])
        ;
    }
}