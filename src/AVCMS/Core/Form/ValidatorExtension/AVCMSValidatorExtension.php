<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:54
 */

namespace AVCMS\Core\Form\ValidatorExtension;

use AVCMS\Core\Form\FormError;
use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Validation\Validator;

class AVCMSValidatorExtension implements ValidatorExtension {
    /**
     * @var FormHandler
     */
    protected $formHandler;

    /**
     * @var \AVCMS\Core\Validation\Validator
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
}