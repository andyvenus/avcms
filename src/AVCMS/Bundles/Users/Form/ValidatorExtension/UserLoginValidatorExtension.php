<?php
/**
 * User: Andy
 * Date: 23/02/2014
 * Time: 19:49
 */

namespace AVCMS\Bundles\Users\Form\ValidatorExtension;

use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Form\ValidatorExtension\ValidatorExtension;
use AVCMS\Bundles\Users\LoginHandler;

class UserLoginValidatorExtension implements ValidatorExtension
{
    /**
     * @var FormHandler
     */
    protected $form_handler;

    /**
     * @var \AVCMS\Bundles\Users\LoginHandler
     */
    protected $login_handler;

    public function __construct(LoginHandler $login_handler)
    {
        $this->login_handler = $login_handler;
    }

    public function setFormHandler(FormHandler $form_handler)
    {
       $this->form_handler = $form_handler;
    }

    public function validate($scope = null, $options = null)
    {
        $fields = $this->form_handler->getFields();

        if (!in_array('identifier', $fields) || !in_array('password', $fields)) {
            throw new \Exception("The UserLoginValidatorExtension validator only works with forms that have a username & password field");
        }
    }

    public function isValid($scope, $options)
    {
        // TODO: Implement isValid() method.
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function fieldHasError($field)
    {
        // TODO: Implement fieldHasError() method.
    }
}