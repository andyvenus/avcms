<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:19
 */

namespace AVCMS\Bundles\AVScripts\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AVScriptsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('update_checker', 'AVCMS\Bundles\AVScripts\UpdateChecker\UpdateChecker')
            ->setArguments(['%app_config%', '%root_dir%', '%avs_api_url%'])
        ;

        $container->register('subscriber.license', 'AVCMS\Bundles\AVScripts\Subscriber\LicenseSubscriber')
            ->setArguments([new Reference('admin.request_matcher'), '%root_dir%', new Reference('security.auth_checker'), new Reference('fragment_handler'), '%app_config%'])
            ->addTag('event.subscriber')
        ;
    }
}
