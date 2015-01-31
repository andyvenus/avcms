<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:19
 */

namespace AVCMS\Bundles\Updater\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UpdaterServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('update_checker', 'AVCMS\Bundles\Updater\UpdateChecker\UpdateChecker')
            ->setArguments(['%app_config%']);
    }
}
