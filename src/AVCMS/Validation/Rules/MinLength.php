<?php

namespace AVCMS\Validation\Rules;


class MinLength extends Rule {
    public function __construct($length)
    {
        $this->length = $length;
    }

    public function assert($param)
    {
        if (strlen($param) >= $this->length) {
            return true;
        }
        else {
            return false;
        }
    }
} 