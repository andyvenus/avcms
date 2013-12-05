<?php

namespace AVCMS\Validation\Rules;


class Numeric extends Rule {
    public function assert($param) {
        return is_numeric($param);
    }
} 