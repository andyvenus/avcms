<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 14:05
 */

namespace AVCMS\Bundles\Adverts\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdvertServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('adverts.choices_provider', 'AVCMS\Bundles\Adverts\Form\ChoicesProvider\AdvertChoicesProvider')
            ->setArguments([new Reference('adverts.model')])
        ;

        $container->register('adverts.model', 'AVCMS\Bundles\Adverts\Model\Adverts')
            ->addTag('model')
        ;
    }
}
