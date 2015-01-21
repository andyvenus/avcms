<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 13:56
 */

namespace AV\Form\Transformer;

class UrlTransformer implements TransformerInterface
{
    public function getId()
    {
        return 'url';
    }

    public function toForm($value)
    {
        return $value;
    }

    public function fromForm($value)
    {
        if (strpos($value, '://') === false) {
            $value = 'http://'.$value;
        }

        return $value;
    }
}
