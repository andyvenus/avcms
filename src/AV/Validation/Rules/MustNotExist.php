<?php

namespace AV\Validation\Rules;

use AV\Model\Model;
use AV\Model\ModelFactory;

class MustNotExist extends Rule implements ModelRuleInterface {

    /**
     * @var string|Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $existsError = "The field '{param_name}' must be unique, value already exists";

    /**
     * @var \AV\Model\ModelFactory
     */
    protected $modelFactory;

    /**
     * @var mixed
     */
    protected $id;

    public function __construct($model, $column, $ignoredId = null)
    {
        $this->model = $model;
        $this->column = $column;
        $this->id = $ignoredId;
    }

    public function assert($param)
    {
        $result = $this->getResult($param);

        if (empty($result)) {
            return true;
        }
        else {
            $this->setError($this->existsError);
            return false;
        }
    }

    protected function getResult($param)
    {
        if (!is_object($this->model) && !isset($this->modelFactory)) {
            throw new \Exception(sprintf("The rule %s requires a ModelFactory to be set using the RuleModelFactoryInjector subscriber", get_class($this)));
        }

        if (!is_object($this->model)) {
            $model = $this->modelFactory->create($this->model);
        }
        else {
            $model = $this->model;
        }

        $query = $model->query()->where($this->column, $param);

        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }

        return $query->first();
    }

    /**
     * Injects the model factory into this rule.
     *
     * REQUIRES that the Events\RuleModelFactoryInjector subscriber is
     * subscribed to the validator.filter.rule event in within the Validator
     *
     * @param ModelFactory $modelFactory
     */
    public function setModelFactory(ModelFactory $modelFactory) {
        $this->modelFactory = $modelFactory;
    }
} 