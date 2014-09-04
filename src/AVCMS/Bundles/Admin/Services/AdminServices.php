<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 15:13
 */

namespace AVCMS\Bundles\Admin\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdminServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('slug.generator', 'AVCMS\Core\SlugGenerator\SlugGenerator');

        $container->register('listener.entity.date', 'AVCMS\Bundles\Admin\Listeners\DateMaker')
            ->addTag('event.subscriber')
        ;

        $container->register('listener.entity.author', 'AVCMS\Bundles\Admin\Listeners\AuthorAssigner')
            ->setArguments(array(new Reference('active.user')))
            ->addTag('event.subscriber')
        ;
    }
}