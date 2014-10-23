<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:39
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TranslationServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('translator', 'AVCMS\Core\Translation\Translator')
            ->setArguments(array('en_GB', new Reference('translator.message_selector'), '%dev_mode%'))
            ->addMethodCall('addLoader', array('array', new Reference('translator.loader.array')))
            ->addMethodCall('addResource', array('array',
                array(
                    'That name is already in use' => 'Arr, that name be already in use',
                    'Name' => 'FRUNCH NAME',
                    'Cat One' => 'Le Category Une',
                    'Published' => 'Pubèlishé',
                    'Submit' => 'Procesèur',
                    'Cannot find an account that has that username or email address' => 'Oh vue du nuet finel the user',
                    'Title' => 'Oh qui le Titlè',
                    'Blog Posts' => 'Meepz'
                ),
                'en_GB'))
        ;

        $container->register('translator.message_selector', 'Symfony\Component\Translation\MessageSelector');

        $container->register('translator.loader.array', 'Symfony\Component\Translation\Loader\ArrayLoader');

        $container->register('twig.translation.extension', 'Symfony\Bridge\Twig\Extension\TranslationExtension')
            ->setArguments(array(new Reference('translator')))
            ->addTag('twig.extension')
        ;
    }
}