<?php

namespace AVCMS\Core\Validation;

use AVCMS\Core\Model\ModelFactory;
use AVCMS\Core\Validation\Handlers\SelfValidatableHandler;
use AVCMS\Core\Validation\Handlers\ValidatableHandler;
use Symfony\Component\Translation\TranslatorInterface;

class Validator
{

    /**
     * @const SCOPE_SHARED Only sub-validate parameters that also appear within the parent
     */
    const SCOPE_SUB_SHARED = 'shared_fields';

    /**
     * @const SCOPE_SHARED Validate all parameters, regardless of if they're shared by the parent or not
     */
    const SCOPE_ALL = 'all';

    /**
     * @const SCOPE_SHARED Only validate the parent parameters
     */
    const SCOPE_PARENT_ONLY = 'parent_only';

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var array
     */
    protected $sub_validation_objects = array();

    /**
     * @var mixed The object we are validating
     */
    protected $validation_obj;

    /**
     * @var ModelFactory
     */
    protected $model_factory;

    /**
     * @var array
     */
    protected $limited_params = array();

    /**
     * @var ValidatableHandler[]
     */
    protected $validatable_handlers;

    /**
     * @var TranslatorInterface
     */
    protected $translator;


    public function __construct($validatable_handlers = null)
    {
        if ($validatable_handlers) {
            $this->validatable_handlers = $validatable_handlers;
        }
        else {
            $this->addValidatableHandler(new SelfValidatableHandler());
        }
    }

    /**
     * @param string|array $param_names
     * @param Rules\Rule $rule
     * @param string $error_message
     * @param bool $ignore_null
     * @param bool $stop_propagation
     */
    public function addRule($param_names, Rules\Rule $rule, $error_message = null, $ignore_null = false, $stop_propagation = false)
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
     * @param mixed $validatable
     * @param null $limit_parameters
     * @param string $validatable_handler
     */
    public function addSubValidation($validatable, $limit_parameters = null, $validatable_handler = 'standard')
    {
        $this->sub_validation_objects[] = array(
            'object' => $validatable,
            'limited_params' => $limit_parameters,
            'handler' => $validatable_handler
        );
    }

    /**
     * @param array $limited_params
     */
    public function limitValidationParams($limited_params = array())
    {
        $this->limited_params = $limited_params;
    }

    /**
     * @param mixed $validatable
     * @param string $handler
     * @param string $scope
     * @param bool $ignore_null
     * @throws \Exception
     */
    public function validate($validatable, $handler = 'standard', $scope = Validator::SCOPE_ALL, $ignore_null = false)
    {
        $this->resetParameters(); // TODO: Is this a good idea at all?
        $this->validation_obj = $validatable;

        if (is_array($validatable)) {
            $this->parameters = $validatable;
        }
        else {
            if (!isset($this->validatable_handlers[$handler])) {
                throw new \Exception("The validation handler $handler does not exist in this validator");
            }

            $this->validatable_handlers[$handler]->getValidationRules($validatable, $this);
            $this->parameters = $this->validatable_handlers[$handler]->getValidationData($validatable, $this);
        }

        $sub_validation_ignore = array();

        $this->errors = array();
        foreach ($this->rules as $rule) {

            if (isset($this->parameters[ $rule['param_name'] ])) {

                // If the parameters to validate have been limited, make sure this parameter is one of those
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
                        if (!$rule['error_message']) {

                            if (!$rule['error_message'] = $rule_obj->getError()) {
                                $rule['error_message'] = "Unidentified {param_name} error"; // TODO: Add rule name to error
                            }
                        }

                        $rule['error_message'] = $this->processError($rule['error_message'], $rule_obj->getRuleData() + array('param_name' => $rule['param_name']));

                        if ($rule['stop_propagation'] == true) {
                            $sub_validation_ignore[] = $rule['param_name'];
                        }

                        $this->errors[] = $rule;
                    }
                }

            }
            elseif ($rule['ignore_null'] == false) {
                $rule['error_message'] = "Parameter '{param_name}' not set";

                $rule['error_message'] = $this->processError($rule['error_message'], array('param_name' => $rule['param_name']));

                if ($rule['stop_propagation'] == true) {
                    $sub_validation_ignore[] = $rule['param_name'];
                }

                $this->errors[] = $rule;
            }
        }

        $this->getSubValidationErrors($scope, $ignore_null, $sub_validation_ignore);
    }

    protected function processError($error_message, $error_parameters = array())
    {
        if (isset($this->translator)) {
            return $this->translator->trans($error_message, $error_parameters);
        }
        else {
            $error_params_updated = array();
            foreach ($error_parameters as $original => $replacement) {
                $error_params_updated['{'.$original.'}'] = $replacement;
            }
            return strtr($error_message, $error_params_updated);
        }
    }

    /**
     * @param string $scope
     * @param bool $ignore_null
     * @param array $ignored_parameters
     * @return null
     */
    public function getSubValidationErrors($scope = Validator::SCOPE_ALL, $ignore_null = false, $ignored_parameters = array())
    {

        // Scope set to parent only, so we don't want to do any sub-validation
        if ($scope == Validator::SCOPE_PARENT_ONLY) {
            return null;
        }

        foreach($this->sub_validation_objects as $validatable)
        {
            $validator = new Validator($this->validatable_handlers);

            if (isset($this->model_factory)) {
                $validator->setModelFactory($this->model_factory);
            }
            if (isset($this->translator)) {
                $validator->setTranslator($this->translator);
            }

            if ($scope != Validator::SCOPE_ALL) {
                if ($validatable['limited_params']) {
                    $limit_params = $validatable['limited_params'];
                }
                else {
                    $limit_params = $this->parameters;
                }

                $validator->limitValidationParams(array_keys($limit_params));
            }

            $validator->validate($validatable['object'], $validatable['handler'], $scope, $ignore_null);

            if (!$validator->isValid($scope)) {
                foreach ($validator->getErrors() as $error) {

                    // If the parameter isn't explicitly set to be ignored
                    if ( !in_array($error['param_name'], $ignored_parameters)
                        && (
                            // If the scope is set to SCOPE_ALL, always proceed. If the scope is set to SCOPE_SHARED, make sure the parent also has that property
                            ( $scope == Validator::SCOPE_ALL )
                            || ( $scope == Validator::SCOPE_SUB_SHARED
                                && isset( $this->parameters[ $error['param_name'] ] )
                            )
                        )
                    )  {

                        // inherit the error
                        $this->errors[] = $error;
                    }
                }
            }
        }
    }

    protected function resetParameters()
    {
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

    public function addValidatableHandler(ValidatableHandler $handler)
    {
        $name = $handler->getName();
        $this->validatable_handlers[$name] = $handler;
    }

    /**
     * @param ModelFactory $model_factory
     */
    public function setModelFactory(ModelFactory $model_factory) {
        $this->model_factory = $model_factory;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}