<?php
/**
 * User: Andy
 * Date: 04/02/15
 * Time: 11:36
 */

namespace AVCMS\Bundles\AVScripts\Form;

use AV\Form\FormBlueprint;

class CopyrightRemovalForm extends FormBlueprint
{
    public function __construct($currentCopyrightMessage)
    {
        $this->setSuccessMessage('Copyright Message Updated');

        $this->add('copyright_message', 'textarea', [
            'label' => 'Copyright Message (can contain HTML)',
            'default' => $currentCopyrightMessage
        ]);
    }
}
