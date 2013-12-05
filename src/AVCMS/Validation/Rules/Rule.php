<?php

namespace AVCMS\Validation\Rules;

abstract class Rule {
    abstract public function assert($param);
} 