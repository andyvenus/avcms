<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 12:38
 */

namespace AV\Form\Tests\Type;

use AV\Form\Tests\Fixtures\ChoicesProviderFixture;
use AV\Form\Type\SelectType;

class SelectTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValidRequestDataMultiple()
    {
        $field = [
            'name' => 'multi_field',
            'options' => [
                'attr' => [
                    'multiple' => true
                ]
            ]
        ];

        $selectType = new SelectType();
        $this->assertTrue($selectType->isValidRequestData($field, ['one']));
    }

    public function testArrayInvalidNonMultiple()
    {
        $field = ['name' => 'test'];

        $selectType = new SelectType();
        $this->assertFalse($selectType->isValidRequestData($field, ['one']));
    }

    public function testStrictRequestData()
    {
        $field = [
            'name' => 'test',
            'options' => [
                'strict' => true,
                'choices' => ['valid_choice' => 'Valid Choice']
            ]
        ];

        $selectType = new SelectType();
        $this->assertTrue($selectType->isValidRequestData($field, 'valid_choice'));
        $this->assertFalse($selectType->isValidRequestData($field, 'invalid_choice'));
    }

    public function testNullRequestData()
    {
        $field = ['name' => 'test'];

        $selectType = new SelectType();
        $this->assertFalse($selectType->isValidRequestData($field, null));
    }

    /**
     * @dataProvider choicesProvider
     * @param $choicesProvider mixed
     */
    public function testDefaultOptionsChoicesProvider($choicesProvider)
    {
        $field = ['name' => 'my_field', 'options' => ['choices_provider' => $choicesProvider]];

        $selectType = new SelectType();

        $finalField = $selectType->getDefaultOptions($field);

        $this->assertCount(2, $finalField['options']['choices']);
    }

    public function choicesProvider()
    {
        return [
            [['class' => 'AV\Form\Tests\Fixtures\ChoicesProviderFixture']],
            [new ChoicesProviderFixture()]
        ];
    }
}
 