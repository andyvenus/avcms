<?php

namespace AVCMS\Core\Validation\Rules;


use AVCMS\Core\Model\ModelFactory;

interface ModelRule {
    public function setModelFactory(ModelFactory $model_factory);
} 