<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:52
 */

namespace AVCMS\Core\Validation\Tests\Fixtures;

use AVCMS\Core\Validation\Rules\Rule;

class FailureRule extends Rule {

    public function assert($param)
    {
        return false;
    }
}