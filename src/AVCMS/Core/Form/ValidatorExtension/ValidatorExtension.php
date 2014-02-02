<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:49
 */

namespace AVCMS\Core\Form\ValidatorExtension;


use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormHandler;

interface ValidatorExtension {
    public function setFormHandler(FormHandler $form_handler);

    public function validate($scope = null, $options = null);

    public function isValid($scope, $options);

    public function getErrors();
}