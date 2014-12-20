<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 19:50
 */

namespace AVCMS\Bundles\Categories\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CategoryServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('subscriber.category_parent', 'AVCMS\Bundles\Categories\Subscribers\SetCategoryParentOnContentSubscriber')
            ->setArguments([new Reference('bundle_manager'), new Reference('model_factory')])
            ->addTag('event.subscriber')
        ;
    }
}
