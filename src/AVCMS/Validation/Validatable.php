<?php

namespace AVCMS\Validation;

interface Validatable
{
    public function getValidationRules(Validator $validator);

    public function getParameters();
} 