<?php
/**
 * User: Andy
 * Date: 18/03/15
 * Time: 19:03
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class MemberListSearchForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('search', 'text', [
            'label' => 'Username or Email'
        ]);

        $this->setMethod('GET');
    }
}
