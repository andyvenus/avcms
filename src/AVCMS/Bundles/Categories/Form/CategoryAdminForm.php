<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 21:33
 */

namespace AVCMS\Bundles\Categories\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class CategoryAdminForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoicesProvider)
    {
        $this->add('name', 'text', [
            'label' => 'Name',
            'attr' => array(
                'data-slug-target' => 'slug'
            )
        ]);

        $this->add('slug', 'text', array(
            'label' => 'URL Slug',
            'required' => true,
            'field_template' => '@admin/form_fields/slug_field.twig'
        ));
    }
}
