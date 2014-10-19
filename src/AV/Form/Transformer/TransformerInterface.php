<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 11:41
 */

namespace AV\Form\Transformer;


interface TransformerInterface {
    public function getId();

    public function toForm($value);

    public function fromForm($value);
}