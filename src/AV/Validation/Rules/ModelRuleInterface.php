<?php

namespace AV\Validation\Rules;


use AV\Model\ModelFactory;

interface ModelRuleInterface {
    public function setModelFactory(ModelFactory $model_factory);
} 