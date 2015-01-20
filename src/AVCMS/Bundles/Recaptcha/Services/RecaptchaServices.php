<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 14:05
 */

namespace AVCMS\Bundles\Recaptcha\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RecaptchaServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('subscriber.recaptcha_form', 'AVCMS\Bundles\Recaptcha\EventSubscriber\RecaptchaFormSubscriber')
            ->setArguments([new Reference('settings_manager')])
            ->addTag('event.subscriber')
        ;
    }
}
