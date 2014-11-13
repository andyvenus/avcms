<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:39
 */

namespace AV\Bundles\Translation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TranslationServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('translator', 'AVCMS\Core\Translation\Translator')
            ->setArguments(array(new Reference('settings_manager'), new Reference('translator.message_selector'), '%dev_mode%'))
            ->addMethodCall('addLoader', array('php', new Reference('translator.loader.php')))
            ->addMethodCall('loadTranslationsFromDirs', ['php', '%root_dir%/webmaster/translations', 'php'])
        ;

        $container->register('translator.message_selector', 'Symfony\Component\Translation\MessageSelector');

        $container->register('translator.loader.php', 'Symfony\Component\Translation\Loader\PhpFileLoader');

        $container->register('twig.translation.extension', 'Symfony\Bridge\Twig\Extension\TranslationExtension')
            ->setArguments(array(new Reference('translator')))
            ->addTag('twig.extension')
        ;
    }
}