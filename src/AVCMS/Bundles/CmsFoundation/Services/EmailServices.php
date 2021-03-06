<?php
/**
 * User: Andy
 * Date: 29/09/2014
 * Time: 12:16
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EmailServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('mailer', 'AVCMS\Core\Email\Mailer')
            ->setArguments([new Reference('settings_manager')])
        ;
    }
}