<?php

namespace AVCMS\Validation\Rules;

use AVCMS\Model\ModelFactory;

class MustNotExist extends Rule implements ModelRule {

    /**
     * @var string
     */
    protected $model;

    protected $column;

    /**
     * @var \AVCMS\Model\ModelFactory
     */
    protected $model_factory;

    public function __construct($model, $column, $id = null)
    {
        $this->model = $model;
        $this->column = $column;
        $this->id = $id;
    }

    public function assert($param)
    {

        $model = $this->model_factory->create($this->model);

        $query = $model->query()->where($this->column, $param);

        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }

        $result = $query->get();

        if (empty($result)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }
} 