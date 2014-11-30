<?php
/**
 * User: Andy
 * Date: 30/11/14
 * Time: 12:12
 */

namespace AV\Form\Tests\Type;

use AV\Form\Type\RadioType;

class RadioTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testIntegerValue()
    {
        $field = ['name' => 'name', 'value' => '1'];

        $radioType = new RadioType();

        $mockHandler = $this->getMockBuilder('AV\Form\FormHandler')->disableOriginalConstructor()->getMock();

        $process = $radioType->makeView($field, [], $mockHandler);
        $this->assertSame(1, $process['value']);
    }
}
 