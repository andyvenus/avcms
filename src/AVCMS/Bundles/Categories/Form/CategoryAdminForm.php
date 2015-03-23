<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 21:33
 */

namespace AVCMS\Bundles\Categories\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Categories\Model\Categories;

class CategoryAdminForm extends FormBlueprint
{
    protected $itemId;

    protected $categories;

    public function __construct($itemId, Categories $categories)
    {
        $this->itemId = $itemId;
        $this->categories = $categories;

        $this->add('name', 'text', [
            'label' => 'Name',
            'attr' => array(
                'data-slug-target' => 'slug'
            )
        ]);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);

        $this->add('slug', 'text', array(
            'label' => 'URL Slug',
            'required' => true,
            'field_template' => '@admin/form_fields/slug_field.twig'
        ));
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist($this->categories, 'slug', $this->itemId));
    }
}
