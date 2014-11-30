<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 12:53
 */

namespace AV\Form\Tests\Fixtures;

use AV\Form\ChoicesProviderInterface;

class ChoicesProviderFixture implements ChoicesProviderInterface
{
    public function getChoices()
    {
        return [
            'choice_one' => 'Choice One',
            'choice_two' => 'Choice Two'
        ];
    }
}