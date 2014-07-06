<?php

namespace AVCMS\Core\Validation\Rules;

use AVCMS\Core\Model\ModelFactory;

class MustNotExist extends Rule implements ModelRule {

    /**
     * @var string
     */
    protected $model;

    protected $column;

    protected $exists_error = "The field '{param_name}' must be unique, value already exists";

    /**
     * @var \AVCMS\Core\Model\ModelFactory
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
            $this->setError($this->exists_error);
            return false;
        }
    }

    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }
} 