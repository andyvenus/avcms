<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 11:33
 */

namespace AVCMS\Core\Form;

use Symfony\Component\Translation\TranslatorInterface;

class FormView implements FormViewInterface
{
    /**
     * @var Array
     */
    protected $fields = array();

    /**
     * @var String
     */
    protected $action = null;

    /**
     * @var String
     */
    protected $method = 'POST';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var String
     */
    protected $submit_button_label = 'Submit';

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var array
     */
    protected $params;

    /**
     * {@inheritdoc}
     */
    public function setFields(array $fields)
    {
        if (array_key_exists('params', $fields)) {
            throw new \Exception("Your form cannot contain a field called 'params' as it clashes with internal functions");
        }

        $this->fields = $this->doFieldTranslations($fields);
    }

    public function doFieldTranslations($fields) {
        $updated_fields = array();

        foreach ($fields as $field) {

            if (isset($field['options']['label'])) {
                $field['options']['label'] = $this->translate($field['options']['label']);
            }

            if (isset($field['options']['choices'])) {
                foreach ($field['options']['choices'] as $value => $label) {
                    $field['options']['choices'][$value] = $this->translate($field['options']['choices'][$value]);
                }
            }

            if (isset($field['fields'])) {
                $field['fields'] = $this->doFieldTranslations($field['fields']);
            }

            if (strpos($field['name'], '[]') === false) {
                $updated_fields[$field['name']] = $field;
            }
            else {
                $updated_fields[] = $field;
            }
        }

        return $updated_fields;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function setAction($url)
    {
        $this->params['action'] = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod($method)
    {
        $this->params['method'] = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->params['name'] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoding($encoding)
    {
        $this->params['encoding'] = $encoding;
    }

    public function getEncoding()
    {
        return $this->params['encoding'];
    }

    /**
     * {@inheritdoc}
     */
    public function setSubmitButtonLabel($label)
    {
        $this->submit_button_label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubmitButtonLabel()
    {
        return $this->translate($this->submit_button_label);
    }

    /**
     * Inject a translator to provide label translations
     *
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Translate a string if the translator has been injected
     *
     * @param $str The string that will be translated
     * @return string
     */
    protected function translate($str)
    {
        if (isset($this->translator)) {
            return $this->translator->trans($str);
        }
        else {
            return $str;
        }
    }

    /**
     * @param $errors FormError[]
     */
    public function setErrors(array $errors)
    {
        foreach ($errors as $error) {
            if ($error->getTranslate() == true) {
                $error->setMessage($this->translate($error->getMessage()));
            }
            $this->errors[] = $error;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($name)
    {
        if (isset($this->fields[$name])) {
            return true;
        }
        else {
            return false;
        }
    }
} 