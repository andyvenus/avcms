<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 11:51
 */

namespace AVCMS\Core\Form\Transformer;

class UnixTimestampTransformer implements TransformerInterface
{
    public function getId()
    {
        return 'unixtimestamp';
    }

    public function toForm($value)
    {
        $time = new \DateTime();
        $time->setTimestamp($value);

        return $time->format('Y-m-d H:i');
    }

    public function fromForm($value)
    {
        $time = new \DateTime($value);

        return $time->getTimestamp();
    }
}