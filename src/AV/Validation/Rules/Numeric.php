<?php

namespace AV\Validation\Rules;


class Numeric extends Rule {

    const INTEGER = 'integer'; // integer, eg 1, 2, 500

    const DECIMAL = 'decimal'; // decimal, eg 1, 1.5, 2, 2.3, 500, 500.1

    private $type;

    protected $error = "'{param_name}' must be a number";

    public function __construct($type = self::INTEGER)
    {
        $this->type = $type;
    }

    public function assert($param)
    {
        if ($this->type == self::INTEGER) {
            if (strpos($param, '.')) {
                return false;
            }
        }

        return is_numeric($param);
    }
} 
