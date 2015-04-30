<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 12:09
 */

namespace AV\Validation\Rules;

class ExactValue extends Rule
{
    protected $value;

    protected $error = 'The field {param_name} must be exactly {required_value}';

    public function __construct($value)
    {
        $this->value = $value;

        $this->ruleData['required_value'] = $value;
    }

    function assert($param)
    {
        if ($param !== $this->value) {
            return false;
        }

        return true;
    }
}
