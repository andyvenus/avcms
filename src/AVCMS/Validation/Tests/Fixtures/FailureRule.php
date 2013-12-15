<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:52
 */

namespace AVCMS\Validation\Tests\Fixtures;

use AVCMS\Validation\Rules\Rule;

class FailureRule extends Rule {

    public function assert($param)
    {
        return false;
    }
}