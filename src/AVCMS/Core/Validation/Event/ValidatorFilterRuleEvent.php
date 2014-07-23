<?php
/**
 * User: Andy
 * Date: 10/07/2014
 * Time: 15:53
 */

namespace AVCMS\Core\Validation\Event;

use AVCMS\Core\Validation\Rules\RuleInterface;
use Symfony\Component\EventDispatcher\Event;

class ValidatorFilterRuleEvent extends Event
{
    /**
     * @var \AVCMS\Core\Validation\Rules\RuleInterface
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