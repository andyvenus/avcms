<?php

namespace AV\Validation;

use AV\Validation\Event\ValidatorFilterRuleEvent;
use AV\Validation\Handlers\SelfValidatableHandler;
use AV\Validation\Handlers\ValidatableHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
    protected $fieldsWithErrors = array();

    /**
     * @var array
     */
    protected $subValidationObjects = array();

    /**
     * @var mixed The object we are validating
     */
    protected $validationObj;

    /**
     * @var array
     */
    protected $limitedParams = array();

    /**
     * @var ValidatableHandlerInterface[]
     */
    protected $validatableHandlers;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param null|array $validatableHandlers
     */
    public function __construct(array $validatableHandlers = null)
    {
        if ($validatableHandlers) {
            $this->validatableHandlers = $validatableHandlers;
        }
        else {
            $this->addValidatableHandler(new SelfValidatableHandler());
        }
    }

    /**
     * @param EventDispatcherInterface $event_dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $event_dispatcher)
    {
        $this->eventDispatcher = $event_dispatcher;
    }

    /**
     * @param string|array $param_names
     * @param Rules\Rule $rule
     * @param string $error_message
     * @param bool $ignore_unset
     * @param bool $stop_propagation
     */
    public function addRule($param_names, Rules\Rule $rule, $error_message = null, $ignore_unset = false, $stop_propagation = false, $readableName = null)
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
                'ignore_unset' => $ignore_unset,
                'readable_name' => $readableName
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
        $this->subValidationObjects[] = array(
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
        $this->limitedParams = $limited_params;
    }

    /**
     * @param mixed $validatable
     * @param string $handler
     * @param string $scope
     * @param bool $ignore_unset
     * @throws \Exception
     */
    public function validate($validatable, $handler = 'standard', $scope = Validator::SCOPE_ALL, $ignore_unset = false)
    {
        $this->resetParameters();
        $this->validationObj = $validatable;

        if (is_array($validatable)) {
            $this->parameters = $validatable;
        }
        else {
            if (!isset($this->validatableHandlers[$handler])) {
                throw new \Exception("The validation handler $handler does not exist in this validator");
            }

            $this->validatableHandlers[$handler]->getValidationRules($validatable, $this);
            $this->parameters = $this->validatableHandlers[$handler]->getValidationData($validatable, $this);
        }

        $sub_validation_ignore = array();

        $this->errors = array();
        foreach ($this->rules as $rule) {
            $parameter_value = $this->getFromMultidimensionalArray($rule['param_name'], $this->parameters);

            if ($parameter_value !== false) {

                // If the parameters to validate have been limited, make sure this parameter is one of those
                if ( ( empty($this->limitedParams) || in_array($rule['param_name'], $this->limitedParams) )) {
                    /** @var $rule_obj Rules\RuleInterface */
                    $rule_obj = $rule['rule'];

                    if (isset($this->eventDispatcher)) {
                        $this->eventDispatcher->dispatch('validator.filter.rule', new ValidatorFilterRuleEvent($rule_obj));
                    }

                    if (!$rule_obj->assert($parameter_value)) {
                        if (!$rule['error_message']) {

                            if (!$rule['error_message'] = $rule_obj->getError()) {
                                $rule['error_message'] = "Unidentified {param_name} error";
                            }
                        }

                        $readableName = (isset($rule['readable_name']) ? $rule['readable_name'] : $rule['param_name']);

                        $rule['error_message'] = $this->processError($rule['error_message'], $rule_obj->getRuleData() + array('param_name' => $readableName));

                        if ($rule['stop_propagation'] === true) {
                            $sub_validation_ignore[] = $rule['param_name'];
                        }

                        $this->errors[] = $rule;
                        $this->fieldsWithErrors[] = $rule['param_name'];
                    }
                }

            }
            elseif ($rule['ignore_unset'] === false) {
                $rule['error_message'] = "Parameter '{param_name}' not set";

                $rule['error_message'] = $this->processError($rule['error_message'], array('param_name' => $rule['param_name']));

                if ($rule['stop_propagation'] === true) {
                    $sub_validation_ignore[] = $rule['param_name'];
                }

                $this->errors[] = $rule;
                $this->fieldsWithErrors[] = $rule['param_name'];
            }
        }

        $this->getSubValidationErrors($scope, $ignore_unset, $sub_validation_ignore);
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

    protected function getFromMultidimensionalArray($str, $array)
    {
        $depth = explode('.', $str);

        foreach ($depth as $key) {
            if (!isset($array[$key])) {
                return false;
            }

            $array = $array[$key];
        }

        return $array;
    }

    /**
     * @param string $scope
     * @param bool $ignore_unset
     * @param array $ignored_parameters
     * @return null
     */
    public function getSubValidationErrors($scope = Validator::SCOPE_ALL, $ignore_unset = false, $ignored_parameters = array())
    {

        // Scope set to parent only, so we don't want to do any sub-validation
        if ($scope == Validator::SCOPE_PARENT_ONLY) {
            return null;
        }

        foreach($this->subValidationObjects as $validatable)
        {
            $validator = new Validator($this->validatableHandlers);

            if (isset($this->eventDispatcher)) {
                $validator->setEventDispatcher($this->eventDispatcher);
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

            $validator->validate($validatable['object'], $validatable['handler'], $scope, $ignore_unset);

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

    public function addValidatableHandler(ValidatableHandlerInterface $handler)
    {
        $name = $handler->getName();
        $this->validatableHandlers[$name] = $handler;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}