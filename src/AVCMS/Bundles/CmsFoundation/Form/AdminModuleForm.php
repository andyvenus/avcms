<?php
/**
 * User: Andy
 * Date: 14/09/2014
 * Time: 14:53
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider\RouteChoicesProvider;
use AVCMS\Core\Form\FormBlueprint;

class AdminModuleForm extends FormBlueprint
{
    public function __construct(RouteChoicesProvider $routesProvider)
    {
        $this->add('title', 'text', array(
            'label' => 'Heading / Title',
            'required' => true
        ));

        $this->add('show_header', 'checkbox', array(
            'label' => 'Show Header',
            'required' => true,
            'default' => 1
        ));

        $this->add('template_style', 'select', array(
            'label' => 'Template Style',
            'required' => true,
            'choices' => array(
                'plain' => 'Plain',
                'contained' => 'Container',
                'none' => 'None'
            )
        ));

        $this->add('limit_routes_array[]', 'select', array(
            'label' => 'Limit to routes (leave blank for all routes)',
            'choices_provider' => $routesProvider,
            'attr' => array(
                'multiple' => 'multiple'
            )
        ));
    }
} 