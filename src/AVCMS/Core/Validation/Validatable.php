<?php

namespace AVCMS\Core\Validation;

interface Validatable
{
    public function getValidationRules(Validator $validator);

    public function getParameters();
} 