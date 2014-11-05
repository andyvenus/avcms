<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 15:13
 */

namespace AVCMS\Bundles\Admin\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdminServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('slug.generator', 'AVCMS\Core\SlugGenerator\SlugGenerator');

        $container->register('listener.entity.date', 'AVCMS\Bundles\Admin\Listeners\DateMaker')
            ->addTag('event.subscriber')
        ;

        $container->register('listener.entity.author', 'AVCMS\Bundles\Admin\Listeners\AuthorAssigner')
            ->setArguments(array(new Reference('security.context')))
            ->addTag('event.subscriber')
        ;
    }
}