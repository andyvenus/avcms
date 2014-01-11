<?php
/**
 * User: Andy
 * Date: 19/12/2013
 * Time: 13:12
 */

namespace AVCMS\Core\Validation\Tests\Rules;

use AVCMS\Core\Validation\Rules\Length;

class LengthTest extends \PHPUnit_Framework_TestCase
{
    // TODO: Test error messages

    public function testMaxSmallerThanMin()
    {
        $this->setExpectedException('AVCMS\Core\Validation\Rules\RuleInvalidException', 'The maximum length cannot be higher than the minimum length');

        $length = new Length(10, 5);
    }

    public function testMaxMinNull()
    {
        $this->setExpectedException('AVCMS\Core\Validation\Rules\RuleInvalidException', 'No minimum or maximum length set');

        $length = new Length();
    }

    /**
     * @dataProvider providerValidMinMax
     *
     * @param $param
     * @param $minimum
     * @param $maximum
     * @param bool $mb_only
     */
    public function testValidMinMax($param, $minimum, $maximum, $mb_only = false)
    {

        if (!$this->mbTest($mb_only)) {
            $this->markTestSkipped('mb_strlen does not exist');
        }

        $length = new Length($minimum, $maximum);

        $this->assertEquals(true, $length->assert($param));
    }

    public function providerValidMinMax()
    {
        return array(
            array('aaaaaaaaaa', 4, 15),
            array('èèèèèèèèèè', 4, 15, true),
            array('a', 1, 1),
            array('aaa aaa,', 7, 10),
            array('', 0, 0),
            array('', 0, 10),
            array(123, 1, 3)
        );
    }

    /**
     * @dataProvider providerInvalidMinMax
     *
     * @param $param
     * @param $minimum
     * @param $maximum
     * @param $mb_only
     */
    public function testInvalidMinMax($param, $minimum, $maximum, $mb_only = false)
    {
        if (!$this->mbTest($mb_only)) {
            $this->markTestSkipped('mb_strlen does not exist');
        }

        $length = new Length($minimum, $maximum);

        $this->assertEquals(false, $length->assert($param));
    }

    public function providerInvalidMinMax()
    {
        return array(
            array('aaaaaaaaaa', 11, 15),
            array('aaaaaaaaaa', 1, 5),
            array('àààààààààà', 11, 15, true),
            array('èèèèèèèèèè', 1, 5, true),
            array('a', 2, 5),
            array('aaa aaa', 1, 6),
            array('aaa aaa', 8, 10),
            array('', 1, 10),
            array(null, 1, 10),
            array(123456, 1, 5),
            array(123, 5, 10)
        );
    }

    /**
     * @dataProvider providerValidExact
     *
     * @param $param
     * @param $length
     * @param bool $mb_only
     */
    public function testValidExact($param, $length, $mb_only = false)
    {
        if (!$this->mbTest($mb_only)) {
            $this->markTestSkipped('mb_strlen does not exist');
        }

        $length = new Length($length, $length);

        $this->assertEquals(true, $length->assert($param));
    }

    public function providerValidExact()
    {
        return array(
            array('abcde', 5),
            array('aàeéé', 5, true),
            array('ab cd', 5),
            array('', 0),
            array('a', 1),
            array('ü', 1, true),
        );
    }

    /**
     * @dataProvider providerInvalidExact
     *
     * @param $param
     * @param $length
     * @param bool $mb_only
     */
    public function testInvalidExact($param, $length, $mb_only = false)
    {
        if (!$this->mbTest($mb_only)) {
            $this->markTestSkipped('mb_strlen does not exist');
        }

        $length = new Length($length, $length);

        $this->assertEquals(false, $length->assert($param));
    }

    public function providerInvalidExact()
    {
        return array(
            array('abcde', 4),
            array('aàeéé', 4, true),
            array('ab cd', 4),
            array('', 1),
            array('a', 2),
            array('ü', 2, true),
            array(null, 1)
        );
    }

    protected function mbTest($mb) {
        if ($mb == true && !function_exists('mb_strlen') && !function_exists('grapheme_strlen')) {
            return false;
        }
        else {
            return true;
        }
    }

}
 