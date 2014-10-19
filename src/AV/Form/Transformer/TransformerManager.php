<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 11:31
 */

namespace AV\Form\Transformer;

class TransformerManager
{
    protected $transformers = array();

    public function registerTransformer($transformer)
    {
        $id = $transformer->getId();

        if (isset($this->transformers[$id])) {
            throw new \Exception("Transformer with ID $id already exists");
        }

        $this->transformers[$id] = $transformer;
    }

    public function toForm($value, $transformer)
    {
        if (!isset($this->transformers[$transformer])) {
            throw new \Exception("Transformer $transformer does not exist");
        }

        return $this->transformers[$transformer]->toForm($value);
    }

    public function fromForm($value, $transformer)
    {
        if (!isset($this->transformers[$transformer])) {
            throw new \Exception("Transformer $transformer does not exist");
        }

        return $this->transformers[$transformer]->fromForm($value);
    }

    public function getTransformers()
    {
        return $this->transformers;
    }
} 