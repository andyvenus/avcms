<?php
/**
 * User: Andy
 * Date: 14/09/2014
 * Time: 14:53
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider\RouteChoicesProvider;
use AVCMS\Bundles\Users\Form\ChoicesProvider\PermissionsChoicesProvider;

class AdminModuleForm extends FormBlueprint
{
    public function __construct(RouteChoicesProvider $routesProvider = null, $templateList = array(), $templateStyles = array(), PermissionsChoicesProvider $permissionsProvider = null, $defaultPermission = null)
    {
        $this->setName('admin_module_form');

        $this->add('title', 'text', [
            'label' => 'Heading / Title',
            'required' => true
        ]);

        $this->add('show_header', 'checkbox', [
            'label' => 'Show Header',
            'required' => true,
            'default' => 1
        ]);

        $this->add('template_type', 'select', [
            'label' => 'Template Type',
            'required' => true,
            'choices' => $templateList
        ]);

        $this->add('template', 'select', [
            'label' => 'Template Style',
            'choices' => $templateStyles
        ]);

        $this->add('limit_routes_array[]', 'select', [
            'label' => 'Limit to pages (leave blank for all pages)',
            'choices_provider' => $routesProvider,
            'attr' => ['multiple' => 'multiple'],
            'choices_translate' => false
        ]);

        $this->add('permissions_array[]', 'select', [
            'label' => 'Permissions (only one needs to match for access)',
            'choices_provider' => $permissionsProvider,
            'attr' => ['multiple' => 'multiple'],
            'default' => $defaultPermission,
            'choices_translate' => false
        ]);
    }
} 
