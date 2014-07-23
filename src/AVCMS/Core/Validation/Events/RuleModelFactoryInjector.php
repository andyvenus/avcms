<?php
/**
 * User: Andy
 * Date: 10/07/2014
 * Time: 16:10
 */

namespace AVCMS\Core\Validation\Events;

use AVCMS\Core\Model\ModelFactory;
use AVCMS\Core\Validation\Event\ValidatorFilterRuleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RuleModelFactoryInjector implements EventSubscriberInterface
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