<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 11:10
 */

namespace AVCMS\Bundles\Videos\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class SubmitVideoForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoices)
    {
        $this->add('file', 'text', [
            'label' => 'Video URL',
            'required' => true,
        ]);

        $this->add('category_id', 'select', [
            'label' => 'Category',
            'choices_provider' => $categoryChoices,
            'strict' => true
        ]);
    }
}
