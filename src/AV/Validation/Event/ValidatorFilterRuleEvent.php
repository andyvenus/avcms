<?php
/**
 * User: Andy
 * Date: 10/07/2014
 * Time: 15:53
 */

namespace AV\Validation\Event;

use AV\Validation\Rules\RuleInterface;
use Symfony\Component\EventDispatcher\Event;

class ValidatorFilterRuleEvent extends Event
{
    /**
     * @var \AV\Validation\Rules\RuleInterface
     */
    protected $rule;

    /**
     * @param RuleInterface $rule
     */
    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    public function getRule()
    {
        return $this->rule;
    }
} 