<?php

namespace AVCMS\Core\Validation;

use AVCMS\Core\Model\ModelFactory;

class Validator {

    /**
     * @const SCOPE_SHARED Only sub-validate parameters that also appear within the parent
     */
    const SCOPE_SHARED = 'shared_fields';

    /**
     * @const SCOPE_SHARED Validate all parameters, regardless of if they're shared by the parent or not
     */
    const SCOPE_ALL = 'all';

    /**
     * @const SCOPE_SHARED Only validate the parent parameters
     */
    const SCOPE_PARENT_ONLY = 'parent_only';

    protected $parameters;

    protected $rules = array();

    protected $errors = array();

    protected $sub_validation_objects = array();

    /**
     * @var 'The object we're validating'
     */
    protected $validation_obj;

    /**
     * @var ModelFactory
     */
    protected $model_factory;

    protected $limited_params = array();

    /**
     * @param string|array $param_names
     * @param Rules\Rule $rule
     * @param string $error_message
     * @param bool $ignore_null
     * @param bool $stop_propagation
     */
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

    /**
     * @param Validatable $obj
     */
    public function addSubValidation(Validatable $obj)
    {
        $this->sub_validation_objects[] = $obj;
    }

    /**
     * @param array $limited_params
     */
    public function limitValidationParams($limited_params = array())
    {
        $this->limited_params = $limited_params;
    }

    /**
     * @param Validatable $obj
     * @param string $scope
     * @param bool $ignore_null
     * @throws \Exception
     */
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
                if ( ( empty($this->limited_params) || in_array($rule['param_name'], $this->limited_params) )) {
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
            elseif ($rule['ignore_null'] == false) {
                $rule['error_message'] = "Parameter '{$rule['param_name']}' not set";
                $this->errors[] = $rule;
            }
        }

        $this->getSubValidationErrors($scope, $ignore_null);
    }

    /**
     * @param string $scope
     * @param bool $ignore_null
     * @return null
     */
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

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param ModelFactory $model_factory
     */
    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }
} 