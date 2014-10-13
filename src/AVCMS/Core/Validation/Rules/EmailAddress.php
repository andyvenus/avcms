<?php
/**
 * User: Andy
 * Date: 29/09/2014
 * Time: 14:07
 */

namespace AVCMS\Core\Validation\Rules;

class EmailAddress extends Rule
{
    protected $error = "Field '{param_name}' requires a valid email address";

    function assert($param)
    {
        return filter_var($param, FILTER_VALIDATE_EMAIL);
    }
}