<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 14:42
 */

namespace AV\Bundles\Form\Type;

use AV\Form\Type\SelectType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareSelectType extends SelectType
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getDefaultOptions($field)
    {
        if (isset($field['options']['choices_provider_service']) && $this->container->has($field['options']['choices_provider_service'])) {
            $field['options']['choices'] = $this->container->get($field['options']['choices_provider_service'])->getChoices();
        }

        return parent::getDefaultOptions($field);
    }
}
