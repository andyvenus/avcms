<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 14:04
 */

namespace AV\Form\Twig;


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
    protected $baseTemplate;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        $this->compiler = $environment->getCompiler();
        $this->baseTemplate = $this->environment->loadTemplate('bootstrap_form.twig');
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
            $this->baseTemplate = $this->environment->loadTemplate('bootstrap_form.twig');
        }
        else {
            $this->baseTemplate = $this->environment->loadTemplate($template);
        }

        return $this->baseTemplate->renderBlock('form_start', array('form' => $form, 'attr' => $attributes));
    }

    public function setFormTemplate($template)
    {
        $this->baseTemplate = $this->environment->loadTemplate($template);
    }

    public function formEnd($form)
    {
        return $this->baseTemplate->renderBlock('form_end', array('form' => $form));
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
            $field = $this->baseTemplate->renderBlock($field_data['type'].'_field', $field_data);
        }

        if (!$field) {
            return "<strong>Error:</strong> No template for field type '$field_data[type]'";
        }

        return $field;
    }

    public function formLabel($fieldData)
    {
        return $this->baseTemplate->renderBlock('form_label', $fieldData);
    }

    public function formSubmitButton($form)
    {
        $label = $form->getSubmitButtonLabel();
        return $this->baseTemplate->renderBlock('submit_button', array('label' => $label));
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

        return $this->baseTemplate->renderBlock($block, array('form_row' => $field, 'attr' => $attributes));
    }

    public function formRows($form, $attributes = array(), $template_overrides = array())
    {
        $fields = $form->getFields();

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $fields, 'attr' => $attributes));
    }

    public function formRowsBefore($form, $beforeField, $attributes = array())
    {
        $fields = $form->getFields();

        $limitedFields = array();

        foreach ($fields as $field_name => $field) {
            if ($field_name == $beforeField) {
                break;
            }
            else {
                $limitedFields[$field_name] = $field;
            }
        }

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $limitedFields, 'attr' => $attributes));
    }

    public function formRowsAfter($form, $beforeField, $attributes = array())
    {
        $fields = $form->getFields();

        $beforeFieldReached = false;
        $limitedFields = array();

        foreach ($fields as $field_name => $field) {
            if ($beforeFieldReached) {
                $limitedFields[$field_name] = $field;
            }

            if ($field_name == $beforeField) {
                $beforeFieldReached = true;
            }
        }

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $limitedFields, 'attr' => $attributes));
    }

    public function form($form, $attributes = array())
    {
        return $this->baseTemplate->renderBlock('form_complete', array('form' => $form, 'attributes' => $attributes));
    }

    public function formMessages($form)
    {
        return $this->baseTemplate->renderBlock('messages', array('form' => $form));
    }
}