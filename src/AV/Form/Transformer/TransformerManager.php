<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 11:31
 */

namespace AV\Form\Transformer;

class TransformerManager
{
    /**
     * @var TransformerInterface[]
     */
    protected $transformers = array();

    /**
     * @param TransformerInterface $transformer
     * @throws \Exception
     */
    public function registerTransformer(TransformerInterface $transformer)
    {
        $id = $transformer->getId();

        if (isset($this->transformers[$id])) {
            throw new \Exception("Transformer with ID $id already exists");
        }

        $this->transformers[$id] = $transformer;
    }

    /**
     * @param $value
     * @param $transformerId
     * @return mixed
     * @throws \Exception
     */
    public function toForm($value, $transformerId)
    {
        if (!isset($this->transformers[$transformerId])) {
            throw new \Exception("Transformer $transformerId does not exist");
        }

        return $this->transformers[$transformerId]->toForm($value);
    }

    /**
     * @param $value
     * @param $transformerId
     * @return mixed
     * @throws \Exception
     */
    public function fromForm($value, $transformerId)
    {
        if (!isset($this->transformers[$transformerId])) {
            throw new \Exception("Transformer $transformerId does not exist");
        }

        return $this->transformers[$transformerId]->fromForm($value);
    }

    /**
     * @return TransformerInterface[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }
} 