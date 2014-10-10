<?php

namespace AVCMS\Core\Validation\Rules;


use AVCMS\Core\Model\ModelFactory;

interface ModelRuleInterface {
    public function setModelFactory(ModelFactory $model_factory);
} 