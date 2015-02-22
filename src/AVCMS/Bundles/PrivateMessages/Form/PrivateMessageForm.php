<?php
/**
 * User: Andy
 * Date: 22/02/15
 * Time: 15:00
 */

namespace AVCMS\Bundles\PrivateMessages\Form;

use AV\Form\FormBlueprint;

class PrivateMessageForm extends FormBlueprint
{
    public function __construct($subjectDefault = null)
    {
        $this->add('subject', 'text', [
            'label' => 'Subject',
            'default' => $subjectDefault,
            'required' => true
        ]);

        $this->add('body', 'textarea', [
            'label' => 'Message',
            'required' => true,
            'attr' => [
                'rows' => 8
            ]
        ]);
    }
}
