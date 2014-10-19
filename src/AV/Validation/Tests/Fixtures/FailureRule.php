<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:52
 */

namespace AV\Validation\Tests\Fixtures;

use AV\Validation\Rules\Rule;

class FailureRule extends Rule
{

    public function assert($param)
    {
        $this->error = "FailureRule default error on {param_name}";
        return false;
    }
}