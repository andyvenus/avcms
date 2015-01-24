<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 12:45
 */

namespace AVCMS\Bundles\ContactUs\Form;

use AV\Form\FormBlueprint;

class ContactUsForm extends FormBlueprint
{
    public function __construct($userLoggedIn = true)
    {
        $this->add('subject', 'text', [
            'label' => 'Subject',
            'required' => true,
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 150]]
            ]
        ]);

        $this->add('body', 'textarea', [
            'label' => 'Message',
            'required' => true,
            'attr' => [
                'rows' => 6
            ],
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 5000]]
            ]
        ]);

        if (!$userLoggedIn) {
            $this->add('email', 'text', [
                'label' => 'Your email address',
                'validation' => [
                    ['rule' => 'EmailAddress'],
                    ['rule' => 'Length', 'arguments' => [null, 200]]
                ],
                'required' => true,
            ]);

            $this->add('recaptcha', 'recaptcha');
        }
    }
}
