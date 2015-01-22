<?php
/**
 * User: Andy
 * Date: 22/01/15
 * Time: 18:34
 */

namespace AVCMS\Bundles\Links\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LinksServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('subscriber.links_menu_item', 'AVCMS\Bundles\Links\EventSubscriber\LinksMenuItemSubscriber')
            ->setArguments([new Reference('links.model')])
            ->addTag('event.subscriber')
        ;

        $container->register('links.model', 'AVCMS\Bundles\Links\Model\Links')
            ->setArguments(['AVCMS\Bundles\Links\Model\Links'])
            ->setFactory([new Reference('model_factory'), 'create'])
        ;
    }
}
