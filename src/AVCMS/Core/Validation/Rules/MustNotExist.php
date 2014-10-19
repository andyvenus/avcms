<?php

namespace AVCMS\Core\Validation\Rules;

use AV\Model\ModelFactory;

class MustNotExist extends Rule implements ModelRuleInterface {

    /**
     * @var string
     */
    protected $model;

    protected $column;

    protected $exists_error = "The field '{param_name}' must be unique, value already exists";

    /**
     * @var \AV\Model\ModelFactory
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
        if (!is_object($this->model) && !isset($this->model_factory)) {
            throw new \Exception(sprintf("The rule %s requires a ModelFactory to be set using the RuleModelFactoryInjector subscriber", get_class($this)));
        }

        if (!is_object($this->model)) {
            $model = $this->model_factory->create($this->model);
        }
        else {
            $model = $this->model;
        }

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

    /**
     * Injects the model factory into this rule.
     *
     * REQUIRES that the Events\RuleModelFactoryInjector subscriber is
     * subscribed to the validator.filter.rule event in within the Validator
     *
     * @param ModelFactory $model_factory
     */
    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }
} 