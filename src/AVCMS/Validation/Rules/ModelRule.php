<?php

namespace AVCMS\Validation\Rules;


use AVCMS\Model\ModelFactory;

interface ModelRule {
    public function setModelFactory(ModelFactory $model_factory);
} 