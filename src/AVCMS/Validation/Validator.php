<?php

namespace AVCMS\Validation;

use AVCMS\Model\ModelFactory;

class Validator {

    const SCOPE_SHARED = 'shared_fields';

    const SCOPE_ALL = 'all';

    const SCOPE_PARENT_ONLY = 'parent_only';

    protected $parameters;

    protected $rules = array();

    protected $errors = array();

    protected $sub_validation_objects = array();

    protected $validation_obj;

    protected $model_factory;

    protected $limited_params = array();

    public function addRule($param_names, Rules\Rule $rule, $error_message, $ignore_null = false, $stop_propagation = false)
    {
        if (!is_array($param_names)) {
            $param_names = array($param_names);
        }

        foreach ($param_names as $param_name) {
            $this->rules[] = array(
                'param_name' => $param_name,
                'rule' => $rule,
                'error_message' => $error_message,
                'stop_propagation' => $stop_propagation,
                'ignore_null' => $ignore_null,
            );
        }
    }

    public function addSubValidation(Validatable $obj)
    {
        $this->sub_validation_objects[] = $obj;
    }

    public function limitValidationParams($limited_params = array())
    {
        $this->limited_params = $limited_params;
    }

    public function validate(Validatable $obj, $scope = Validator::SCOPE_ALL, $ignore_null = false)
    {
        $this->resetParameters();
        $this->validation_obj = $obj;

        $obj->getValidationRules($this);
        $this->parameters = $obj->getParameters();

        $this->errors = array();
        foreach ($this->rules as $rule) {
            if (isset($this->parameters[ $rule['param_name'] ])) {

                // If the parameters to validate are limited, make sure this parameter is one of those
                if ( ( isset ($this->limited_params) && in_array($rule['param_name'], $this->limited_params) )) {
                    /** @var $rule_obj Rules\Rule */
                    $rule_obj = $rule['rule'];

                    if (method_exists($rule_obj, 'setModelFactory')) {
                        if (isset($this->model_factory)) {
                            $rule_obj->setModelFactory($this->model_factory);
                        }
                        else {
                            throw new \Exception("A rule for parameter '{$rule['param_name']}' requires a model factory. Set using Validator::setModelFactory");
                        }
                    }

                    if ( ! $rule_obj->assert( $this->parameters[ $rule['param_name'] ] )) {
                        $this->errors[] = $rule;
                    }
                }

            }
            elseif ($ignore_null == false) {
                $this->errors[] = $rule;
                $rule['error_message'] = "Parameter '{$rule['param_name']}' not set";
            }
        }

        $this->getSubValidationErrors($scope, $ignore_null);
    }

    public function getSubValidationErrors($scope = Validator::SCOPE_ALL, $ignore_null = false)
    {

        if ($scope == Validator::SCOPE_PARENT_ONLY) {
            return null;
        }

        foreach($this->sub_validation_objects as $svo)
        {
            $validator = new Validator();
            $validator->setModelFactory($this->model_factory);

            if ($scope != Validator::SCOPE_ALL) {
                $validator->limitValidationParams(array_keys($this->parameters));
            }

            $validator->validate($svo, $scope, $ignore_null);

            if (!$validator->isValid($scope)) {
                foreach ($validator->getErrors() as $error) {
                    // If the scope is set to SCOPE_ALL, always proceed. If the scope is set to SCOPE_SHARED, make sure the parent also has that property
                    if ( ( $scope == Validator::SCOPE_ALL ) || ( $scope == Validator::SCOPE_SHARED && isset( $this->parameters[ $error['param_name'] ] ) ) )  {
                        // inherit the error
                        $this->errors[] = $error;
                    }
                }
            }
        }
    }

    protected function resetParameters()
    {
        $this->rules = array();
        $this->parameters = array();
        $this->errors = array();
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }
} 