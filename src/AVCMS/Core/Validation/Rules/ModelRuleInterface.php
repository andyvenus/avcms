<?php

namespace AVCMS\Core\Validation\Rules;


use AV\Model\ModelFactory;

interface ModelRuleInterface {
    public function setModelFactory(ModelFactory $model_factory);
} 