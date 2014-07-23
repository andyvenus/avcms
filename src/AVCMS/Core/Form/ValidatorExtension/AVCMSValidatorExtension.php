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
    protected $form_handler;

    /**
     * @var \AVCMS\Core\Validation\Validator
     */
    protected $validator;

    /**
     * @var boolean
     */
    protected $validation_run = false;

    /**
     * @var array
     */
    protected $invalid_params;

    public function __construct(Validator $validator, $entity_handler = null)
    {
        $this->validator = $validator;
    }

    public function setFormHandler(FormHandler $form_handler)
    {
        $this->form_handler = $form_handler;
    }

    public function validate($scope = Validator::SCOPE_ALL, $options = null)
    {
        if (!$this->validation_run) {
            $form = $this->form_handler->getFormBlueprint();

            if (method_exists($form, 'getValidationRules')) {
                $form->getValidationRules($this->validator);
            }

            $entities = $this->form_handler->saveToAndGetClonedEntities();

            if (!empty($entities)) {
                foreach ($entities as $entity) {
                    $this->validator->addSubValidation($entity['entity'], $entity['fields']);
                }
            }

            $this->validator->validate($this->form_handler->getData(), 'standard', $scope);

            $this->validation_run = true;
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

        if (!$this->validation_run) {
            $this->validate($scope, $options);
        }

        return $this->validator->isValid();
    }

    public function getErrors()
    {
        $errors = $this->validator->getErrors();

        $error_objects = array();
        // Errors must be converted to FormError objects
        foreach ($errors as $error) {
            $error_objects[] = new FormError($error['param_name'], $error['error_message'], false);
        }

        return $error_objects;
    }

    public function fieldHasError($field)
    {
        if (!isset($this->invalid_params)) {
            $invalid_params = array();
            $errors = $this->validator->getErrors();

            foreach ($errors as $error) {
                // Convert error parameter name from a.string.like.this to a[string][like][this]
                if (strpos($error['param_name'], '.') !== false) {
                    $exploded_name = explode('.', $error['param_name']);

                    $param_name = array_shift($exploded_name);

                    foreach ($exploded_name as $name_param) {
                        $param_name .= '['.$name_param.']';
                    }
                }
                else {
                    $param_name = $error['param_name'];
                }

                $invalid_params[] = $param_name;
            }

            $this->invalid_params = $invalid_params;
        }

        return in_array($field, $this->invalid_params);
    }
}