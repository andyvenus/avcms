<?php

namespace AVCMS\Core\Validation\Rules;

abstract class Rule {
    abstract public function assert($param);
} 