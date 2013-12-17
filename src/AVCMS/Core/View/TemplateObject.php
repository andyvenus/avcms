<?php
namespace AVCMS\Core\View;

use AVCMS\Core\Model\Entity;

class TemplateObject implements \ArrayAccess {
    public function __construct(Entity $data_object) {
        $this->data_object = $data_object;
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        return $this->data_object->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}