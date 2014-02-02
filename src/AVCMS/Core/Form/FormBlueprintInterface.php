<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 20:45
 */
namespace AVCMS\Core\Form;

/**
 * Interface FormBlueprintInterface
 * @package AVCMS\Core\FormBlueprint
 *
 * Interface for forms to allow adding/editing/removing of form fields and form meta data
 */
interface FormBlueprintInterface
{
    /**
     * Add a field to the form
     *
     * @param string $name The field name
     * @param string $type The field type, for valid types see TODO: DocumentationUrl
     * @param array $options The options for the field, see TODO: DocumentationUrl2
     * @return $this
     * @throws \Exception
     */
    public function add($name, $type, $options = array());

    /**
     * Add a field before an existing field
     *
     * @param string $offset Field name that the new field will appear before
     * @param string $name
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addBefore($offset, $name, $type, $options = array());

    /**
     * Add a field after an existing field
     *
     * @param string $offset Field name that the new field will appear after
     * @param string $name
     * @param string $type
     * @param array $options
     * @return $this
     */
    public function addAfter($offset, $name, $type, $options = array());

    /**
     * Replace an existing field in the form
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @param bool $add_if_not_exist
     * @return $this
     * @throws \Exception
     */
    public function replace($name, $type, $options = array(), $add_if_not_exist = false);

    /**
     * Remove a field from the form
     *
     * @param string $name The name of the field to be removed
     * @return $this
     */
    public function remove($name);

    /**
     * Check if the form has a certain field
     *
     * @param string $name The name of the field to check for
     * @return bool
     */
    public function has($name);

    /**
     * Retrieve a field from the form
     *
     * @param string $name The name of the field to retrieve
     * @return null
     */
    public function get($name);

    /**
     * Get an array of all the field names from this form
     *
     * @return array
     */
    public function getFieldNames();

    /**
     * Retrieve the default field values
     *
     * @return array
     */
    public function getDefaultData();

    /**
     * Get all the form fields
     *
     * @return array
     */
    public function getAll();

    /**
     * Set the URL and HTTP method the form will submit to
     *
     * @param $url The submit URL
     * @param null|string $type Either POST or GET
     * @return $this
     */
    public function setAction($url, $type = null);

    /**
     * Get the URL the form should submit to
     *
     * @return null|string
     */
    public function getAction();

    /**
     * Get the form method, either POST or GET
     *
     * @return string
     */
    public function getMethod();

    /**
     * A method for inserting a new value into an array after or before a certain key
     *
     * @param array $array The starting array
     * @param string $key The key to add the new value before or after
     * @param array $data The new value(s) to be added
     * @param bool $insert_before Set to insert before, default is after
     * @return array
     */
    public function insertAfterKey(array $array, $key, $data = array(), $insert_before = false);

    /**
     * Get the name of the form
     *
     * @return string
     */
    public function getName();

    /**
     * Set the name of the form
     *
     * @param $name
     * @return mixed
     */
    public function setName($name);
}