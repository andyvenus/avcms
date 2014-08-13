<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:39
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Translation implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('translator', 'AVCMS\Core\Translation\Translator')
            ->setArguments(array('en_GB', new \Symfony\Component\Translation\MessageSelector(), '%dev_mode%'))
            ->addMethodCall('addLoader', array('array', new \Symfony\Component\Translation\Loader\ArrayLoader()))
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
    }
}