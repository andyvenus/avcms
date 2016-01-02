<?php
/**
 * User: Andy
 * Date: 10/05/15
 * Time: 20:11
 */

namespace AVCMS\Bundles\Videos\Form;

use AV\Form\FormBlueprint;

class ImportVideoAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('url', 'text', [
            'label' => 'URL'
        ]);
    }
}
