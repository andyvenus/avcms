<?php

namespace AVCMS\Bundles\Games\Model;

class FeedGame extends Game
{
    /**
     * @var array|Entity[] Any entities assigned via the magic __get method
     */protected $subEntities = array (
    );
    /**
     * @var array The data this entity is holding
     */protected $data = array (
    );

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->subEntities[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->subEntities[$name] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->subEntities[$name]);
    }

    public function getAllSubEntities()
    {
        return $this->subEntities;
    }

    public function setCategory($value)
    {
        $this->set("category", $value);
    }

    public function getCategory()
    {
        return $this->get("category");
    }

    public function getDownloadable()
    {
        return $this->get("downloadable");
    }

    public function setDownloadable($value)
    {
        $this->set("downloadable", $value);
    }

    public function setFileType($value)
    {
        $this->set("file_type", $value);
    }

    public function getFileType()
    {
        return $this->get("file_type");
    }

    public function setProvider($value)
    {
        $this->set("provider", $value);
    }

    public function getProvider()
    {
        return $this->get("provider");
    }

    public function getProviderId()
    {
        return $this->get("provider_id");
    }

    public function setProviderId($value)
    {
        $this->set("provider_id", $value);
    }

    public function setStatus($value)
    {
        $this->set("status", $value);
    }

    public function getStatus()
    {
        return $this->get("status");
    }

    public function setTags($value)
    {
        $this->set("tags", $value);
    }

    public function getTags()
    {
        return $this->get("tags");
    }

    public function getValidationData()
    {
        return $this->toArray();
    }

    public function getValidationRules(\AV\Validation\Validator $validator)
    {
        $this->validationRules($validator);

        foreach ($this->subEntities as $subEntity) {
            if (is_a($subEntity, 'AV\Validation\Validatable')) {
                $validator->addSubValidation($subEntity);
            }
        }
    }

    public function __clone()
    {
        foreach($this->subEntities as $name => $obj) {
            if (is_object($obj)) {
                $this->subEntities[$name] = clone $obj;
            }
        }
    }

    /**
     * @param $name
     * @param Entity $entity
     */
    public function addSubEntity($name, \AV\Model\Entity $entity)
    {
        $this->subEntities[$name] = $entity;
    }

    public function fromArray(array $data, $ignoreUnusable = false)
    {
        foreach ($data as $key => $value) {
            $key = str_replace('_', '', $key);

            if (!method_exists($this, 'set'.$key)) {
                if ($ignoreUnusable === false) {
                    throw new \Exception("This entity does not have a method for the data named $key");
                }
                else {
                    continue;
                }
            }

            $this->{'set'.$key}($value);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->subEntities)) {
            return $this->data;
        }
        else {
            $data = $this->data;
            foreach ($this->subEntities as $sub_entity) {
                if (is_a($sub_entity, 'AV\Model\ExtensionEntity')) {

                    $sub_data = $sub_entity->toArray();
                    $sub_data_prefixed = array();

                    foreach ($sub_data as $param_name => $param_data) {
                        $sub_data_prefixed[$sub_entity->getPrefix().'__'.$param_name] = $param_data;
                    }

                    $data = array_merge($data, $sub_data_prefixed);
                }
            }

            return $data;
        }
    }

    public function validationRules(\AV\Validation\Validator $validator)
    {
    }

    /**
     * @param $param
     * @param $value
     */
    protected function set($param, $value)
    {
        $this->data[$param] = $value;
    }

    /**
     * @param $param
     * @return null|string|int
     */
    protected function get($param)
    {
        if (isset($this->data[$param])) {
            return $this->data[$param];
        }
        else {
            return null;
        }
    }
}