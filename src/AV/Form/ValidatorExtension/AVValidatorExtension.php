<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:54
 */

namespace AV\Form\ValidatorExtension;

use AV\Form\FormError;
use AV\Form\FormHandler;
use AV\Validation\Validator;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AVValidatorExtension implements ValidatorExtension, ContainerAwareInterface
{
    /**
     * @var FormHandler
     */
    protected $formHandler;

    /**
     * @var \AV\Validation\Validator
     */
    protected $validator;

    /**
     * @var boolean
     */
    protected $validationRun = false;

    /**
     * @var array
     */
    protected $invalidParams;

    /**
     * @var $container ContainerInterface
     */
    protected $container;

    public function __construct(Validator $validator, $entityHandler = null)
    {
        $this->validator = $validator;
    }

    public function setFormHandler(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    public function validate($scope = Validator::SCOPE_ALL, $options = null)
    {
        if (!$this->validationRun) {
            $form = $this->formHandler->getFormBlueprint();

            if (method_exists($form, 'getValidationRules')) {
                $form->getValidationRules($this->validator);
            }

            $this->doFieldValidationRules($form->getAll());

            $entities = $this->formHandler->saveToAndGetClonedEntities();

            if (!empty($entities)) {
                foreach ($entities as $entity) {
                    $this->validator->addSubValidation($entity['entity'], $entity['fields']);
                }
            }

            $this->validator->validate($this->formHandler->getData(), 'standard', $scope);

            $this->validationRun = true;
        }
        else {
            throw new \Exception("Can't validate twice, use existing validation result");
        }
    }

    public function isValid($scope, $options)
    {
        if ($scope == null) {
            $scope = Validator::SCOPE_ALL;
        }

        if (!$this->validationRun) {
            $this->validate($scope, $options);
        }

        return $this->validator->isValid();
    }

    public function getErrors()
    {
        $errors = $this->validator->getErrors();

        $errorObjects = array();
        // Errors must be converted to FormError objects
        foreach ($errors as $error) {
            $errorObjects[] = new FormError($error['param_name'], $error['error_message'], false);
        }

        return $errorObjects;
    }

    public function fieldHasError($field)
    {
        if (!isset($this->invalidParams)) {
            $invalidParams = array();
            $errors = $this->validator->getErrors();

            foreach ($errors as $error) {
                // Convert error parameter name from a.string.like.this to a[string][like][this]
                if (strpos($error['param_name'], '.') !== false) {
                    $explodedName = explode('.', $error['param_name']);

                    $paramName = array_shift($explodedName);

                    foreach ($explodedName as $nameParam) {
                        $paramName .= '['.$nameParam.']';
                    }
                }
                else {
                    $paramName = $error['param_name'];
                }

                $invalidParams[] = $paramName;
            }

            $this->invalidParams = $invalidParams;
        }

        return in_array($field, $this->invalidParams);
    }

    protected function doFieldValidationRules($fields)
    {
        foreach ($fields as $field) {
            if (isset($field['options']['validation'])) {
                foreach ($field['options']['validation'] as $validation) {
                    // $validation['class'], $validation['arguments']
                    if (isset($validation['class']) && class_exists($validation['class'])) {
                        $class = $validation['class'];
                    }
                    elseif (isset($validation['rule']) && class_exists('AV\Validation\Rules\\'.$validation['rule'])) {
                        $class = 'AV\Validation\Rules\\' . $validation['rule'];
                    }
                    else {
                        continue;
                    }

                    $validation['arguments'] = (isset($validation['arguments']) ? $validation['arguments'] : array());

                    foreach ($validation['arguments'] as $key => $argument) {
                        if (is_string($argument) && false !== strpos($argument, '%')) {
                            $serviceName = explode('%', $argument)[1];
                            if ($this->container->has($serviceName)) {
                                $validation['arguments'][$key] = $this->container->get($serviceName);
                            }
                            else {
                                throw new \Exception(sprintf('Service %s not found for use in validation', $serviceName));
                            }
                        }
                    }

                    $r = new ReflectionClass($class);
                    $rule = $r->newInstanceArgs($validation['arguments']);

                    $errorMessage = (isset($validation['error_message']) ? $validation['error_message'] : null);
                    //$ignoreUnset = (isset($validation['ignore_unset']) ? $validation['ignore_unset'] : false);
                    $ignoreUnset = true; // true because
                    $label = (isset($field['options']['label']) ? $field['options']['label'] : null);

                    $this->validator->addRule($field['name'], $rule, $errorMessage, $ignoreUnset, false, $label);
                }
            }
        }
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}