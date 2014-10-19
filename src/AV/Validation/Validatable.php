<?php

namespace AV\Validation;

interface Validatable
{
    public function getValidationRules(Validator $validator);

    public function getValidationData();
}