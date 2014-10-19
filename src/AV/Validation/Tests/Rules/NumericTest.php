<?php
/**
 * User: Andy
 * Date: 19/12/2013
 * Time: 11:26
 */

namespace AV\Validation\Tests\Rules;


use AV\Validation\Rules\Numeric;

class NumericTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider providerIntegerParams
     *
     * @param $parameter
     * @param $expected
     */
    public function testIntegerValidation($parameter, $expected)
    {
        $numeric = new Numeric('integer');

        $this->assertEquals($numeric->assert($parameter), $expected);
    }

    public function providerIntegerParams()
    {
        $values = array(
            array('1', true),
            array(1, true),
            array(1.1, false),
            array(54.45, false),
            array(500004.43345345, false),
            array("2.3", false),
            array(123456789, true),
            array(+0123.45e6, true),
            array("0xf4c3b00c", true),
            array(0xf4c3b00c, true)
        );

        return array_merge($values, $this->invalids());
    }

    /**
     * @dataProvider providerDecimalParams
     * @param $parameter
     * @param $expected
     */
    public function testDecimalValidation($parameter, $expected)
    {
        $numeric = new Numeric('decimal');

        $this->assertEquals($numeric->assert($parameter), $expected);
    }

    /**
     * @return array
     */
    public function providerDecimalParams()
    {
        $values = array(
            array('1', true),
            array(1, true),
            array(1.1, true),
            array(54.45, true),
            array(500004.43345345, true),
            array("2.3", true),
            array(123456789, true),
            array(+0123.45e6, true),
            array("0xf4c3b00c", true),
            array(0xf4c3b00c, true),
        );

        return array_merge($values, $this->invalids());
    }

    public function invalids()
    {
        return array(
            array('Abcdefg', false),
            array('123 456', false),
            array('123a', false),
            array('a123', false),
            array('', false)
        );
    }
}
 