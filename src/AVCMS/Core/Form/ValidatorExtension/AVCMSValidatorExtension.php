<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:54
 */

namespace AVCMS\Core\Form\ValidatorExtension;

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
            $form = $this->form_handler->getForm();

            if (!method_exists($form, 'getValidationRules')) {
                $form_class = get_class($form);
                throw new \Exception("The form you're trying to validate ($form_class) does not have a 'getValidationRules' method");
            }

            $form->getValidationRules($this->validator);

            foreach ($this->form_handler->getEntities() as $entity) {
                $this->validator->addSubValidation($entity['entity'], $entity['fields']);
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
        return $this->validator->getErrors();
    }
}