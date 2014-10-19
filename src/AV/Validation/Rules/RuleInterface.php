<?php
/**
 * User: Andy
 * Date: 10/07/2014
 * Time: 15:58
 */

namespace AV\Validation\Rules;

interface RuleInterface
{
    function assert($param);

    function getError();

    function getRuleData();
}