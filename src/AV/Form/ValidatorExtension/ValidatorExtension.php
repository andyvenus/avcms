<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:49
 */

namespace AV\Form\ValidatorExtension;


use AV\Form\FormBlueprint;
use AV\Form\FormHandler;

interface ValidatorExtension {
    /**
     * Sets the form handler
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function setFormHandler(FormHandler $formHandler);

    /**
     * Validate the form
     *
     * @param null $scope
     * @param null $options
     * @return mixed
     */
    public function validate($scope = null, $options = null);

    /**
     * Check if the form is valid or not
     *
     * @param $scope
     * @param $options
     * @return mixed
     */
    public function isValid($scope, $options);

    /**
     * Get errors, should return an array of FormError objects
     *
     * @return \AV\Form\FormError[]
     */
    public function getErrors();

    /**
     * Check if a field has any errors
     *
     * @param $field
     * @return mixed
     */
    public function fieldHasError($field);
}