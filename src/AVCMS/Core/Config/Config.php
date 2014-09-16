<?php
/**
 * User: Andy
 * Date: 14/08/2014
 * Time: 11:43
 */

namespace AVCMS\Core\Config;

class Config implements \ArrayAccess
{
    public function __construct(array $config)
    {
        $this->configArray = $config;
        $this->config = $this->arrayToObject($this->configArray);
    }

    public function __get($name)
    {
        if (!isset($this->config->$name)) {
            return null;
        }

        return $this->config->$name;
    }

    public function __isset($name)
    {
        return isset($this->config->$name);
    }

    public function offsetExists($offset)
    {
        return isset($this->configArray[$offset]);
    }

    public function offsetGet($offset)
    {
        return (isset($this->configArray[$offset]) ? $this->configArray[$offset] : null);
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception("Cannot set config values using array access");
    }

    public function offsetUnset($offset)
    {
        throw new \Exception("Cannot unset config values using array access");
    }

    public function getConfigArray()
    {
        return $this->configArray;
    }

    public function getConfigObject()
    {
        return $this->config;
    }

    protected function arrayToObject($array)
    {
        if (is_array($array)) {
            return (object) array_map(array($this, 'arrayToObject'), $array);
        }
        else {
            return $array;
        }
    }

    public function setSetting($name, $value)
    {
        $this->configArray[$name] = $value;
        $this->config->$name = $this->arrayToObject($value);
    }
} 