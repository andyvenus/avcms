<?php
/**
 * User: Andy
 * Date: 10/07/2014
 * Time: 16:10
 */

namespace AV\Model\Subscriber;

use AV\Model\ModelFactory;
use AV\Validation\Event\ValidatorFilterRuleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InjectModelIntoValidationRuleSubscriber implements EventSubscriberInterface
{
    protected $model_factory;

    public function __construct(ModelFactory $model_factory)
    {
        $this->model_factory = $model_factory;
    }

    public function injectModelFactory(ValidatorFilterRuleEvent $event)
    {
        $rule = $event->getRule();

        if (method_exists($rule, 'setModelFactory')) {
            $rule->setModelFactory($this->model_factory);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'validator.filter.rule' => array('injectModelFactory')
        );
    }
}