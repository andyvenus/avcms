<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 14:04
 */

namespace AVCMS\Core\Form\Twig;


class FormExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Twig_Compiler
     */
    protected $compiler;

    /**
     * @var \Twig_Template
     */
    protected $base_template;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        $this->compiler = $environment->getCompiler();
        $this->base_template = $this->environment->loadTemplate('bootstrap_form.twig');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_form';
    }

    public function getFunctions()
    {
        return array(
            'form_field' => new \Twig_SimpleFunction('form_field',
                    array($this, 'formField'),
                    array('is_safe' => array('html')
                )
            ),
            'form_label' => new \Twig_SimpleFunction('form_label',
                    array($this, 'formLabel'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_start' => new \Twig_SimpleFunction('form_start',
                    array($this, 'formStart'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_end' => new \Twig_SimpleFunction('form_end',
                    array($this, 'formEnd'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_submit_button' => new \Twig_SimpleFunction('form_submit_button',
                    array($this, 'formSubmitButton'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_row' => new \Twig_SimpleFunction('form_row',
                    array($this, 'formRow'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows' => new \Twig_SimpleFunction('form_rows',
                    array($this, 'formRows'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows_before' => new \Twig_SimpleFunction('form_rows_before',
                    array($this, 'formRowsBefore'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows_after' => new \Twig_SimpleFunction('form_rows_after',
                    array($this, 'formRowsAfter'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_messages' => new \Twig_SimpleFunction('form_messages',
                array($this, 'formMessages'),
                array('is_safe' => array('html')
            )),
            'form' => new \Twig_SimpleFunction('form',
                array($this, 'form'),
                array('is_safe' => array('html')
            ))
        );
    }

    public function formStart($form, $attributes = array(), $template = null)
    {
        if ($template == null) {
            $this->base_template = $this->environment->loadTemplate('bootstrap_form.twig');
        }
        else {
            $this->base_template = $this->environment->loadTemplate($template);
        }

        return $this->base_template->renderBlock('form_start', array('form' => $form, 'attr' => $attributes));
    }

    public function setFormTemplate($template)
    {
        $this->base_template = $this->environment->loadTemplate($template);
    }

    public function formEnd($form)
    {
        return $this->base_template->renderBlock('form_end', array('form' => $form));
    }

    public function formField($field_data, $attributes = array())
    {
        if (isset($field_data['options']['attr']) && is_array($field_data['options']['attr'])) {
            $field_data['attr'] = array_merge($field_data['options']['attr'], $attributes);
        }
        else {
            $field_data['attr'] = $attributes;
        }

        /*
        if (isset($field_data['options']['field_template'])) {
            $template = $field_data['options']['field_template'];
        }
        else {
            $template = $field_data['type'].'_field';
        }
        */

        if (isset($field_data['options']['field_template'])) {
            $template = $this->environment->loadTemplate($field_data['options']['field_template']);

            unset($field_data['options']['field_template']);
            $field = $template->render(array('field' => $field_data));
        }
        else {
            $field = $this->base_template->renderBlock($field_data['type'].'_field', $field_data);
        }

        if (!$field) {
            return "<strong>Error:</strong> No template for field type '$field_data[type]'";
        }

        return $field;
    }

    public function formLabel($field_data)
    {
        return $this->base_template->renderBlock('form_label', $field_data);
    }

    public function formSubmitButton($form)
    {
        $label = $form->getSubmitButtonLabel();
        return $this->base_template->renderBlock('submit_button', array('label' => $label));
    }

    public function formRow($field, $attributes = array())
    {
        if ($field['type'] == 'hidden' || $field['type'] == 'collection') {
            $block = 'hidden_row';
        }
        elseif ($field['type'] == 'checkbox') {
            $block = 'checkbox_row';
        }
        else {
            $block = 'form_label_row';
        }

        return $this->base_template->renderBlock($block, array('form_row' => $field, 'attr' => $attributes));
    }

    public function formRows($form, $attributes = array(), $template_overrides = array())
    {
        $fields = $form->getFields();

        return $this->base_template->renderBlock('form_rows', array('form_rows' => $fields, 'attr' => $attributes));
    }

    public function formRowsBefore($form, $before_field, $attributes = array())
    {
        $fields = $form->getFields();

        $limited_fields = array();

        foreach ($fields as $field_name => $field) {
            if ($field_name == $before_field) {
                break;
            }
            else {
                $limited_fields[$field_name] = $field;
            }
        }

        return $this->base_template->renderBlock('form_rows', array('form_rows' => $limited_fields, 'attr' => $attributes));
    }

    public function formRowsAfter($form, $before_field, $attributes = array())
    {
        $fields = $form->getFields();

        $before_field_reached = false;
        $limited_fields = array();

        foreach ($fields as $field_name => $field) {
            if ($before_field_reached) {
                $limited_fields[$field_name] = $field;
            }

            if ($field_name == $before_field) {
                $before_field_reached = true;
            }
        }

        return $this->base_template->renderBlock('form_rows', array('form_rows' => $limited_fields, 'attr' => $attributes));
    }

    public function form($form, $attributes = array())
    {
        return $this->base_template->renderBlock('form_complete', array('form' => $form, 'attributes' => $attributes));
    }

    public function formMessages($form)
    {
        return $this->base_template->renderBlock('messages', array('form' => $form));
    }
}