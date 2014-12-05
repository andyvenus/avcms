<?php
/**
 * User: Andy
 * Date: 05/12/14
 * Time: 13:21
 */

namespace AV\Validation\Tests\Rules;


use AV\Validation\Rules\MustNotExist;

class MustNotExistTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $modelReturnValue
     * @param $expectedResult
     * @throws \Exception
     *
     * @dataProvider baseProvider
     */
    public function testModelFactoryRule($modelReturnValue, $expectedResult)
    {
        $rule = new MustNotExist('Test\For\Model', 'foo');

        $queryBuilder = $this->getMockQueryBuilder();
        $queryBuilder->expects($this->once())
            ->method('first')
            ->willReturn($modelReturnValue);

        $modelFactory = $this->getMockModelFactory();
        $modelFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->getMockModel($queryBuilder))
        ;

        $rule->setModelFactory($modelFactory);

        $this->assertEquals($expectedResult, $rule->assert('my-string'));
    }

    /**
     * @param $modelReturnValue
     * @param $expectedResult
     * @throws \Exception
     *
     * @dataProvider baseProvider
     */
    public function testDirectModelRule($modelReturnValue, $expectedResult)
    {
        $queryBuilder = $this->getMockQueryBuilder();
        $queryBuilder->expects($this->once())
            ->method('first')
            ->willReturn($modelReturnValue);

        $rule = new MustNotExist($this->getMockModel($queryBuilder), 'foo');

        $this->assertEquals($expectedResult, $rule->assert('my-string'));
    }

    /**
     * @param $modelReturnValue
     * @param $expectedResult
     * @throws \Exception
     *
     * @dataProvider baseProvider
     */
    public function testIdExcluded($modelReturnValue, $expectedResult)
    {
        $queryBuilder = $this->getMockQueryBuilder();
        $queryBuilder->expects($this->once())
            ->method('first')
            ->willReturn($modelReturnValue);
        $queryBuilder->expects($this->exactly(2))
            ->method('where')
            ->willReturn($queryBuilder);

        $rule = new MustNotExist($this->getMockModel($queryBuilder), 'foo', 1);

        $this->assertEquals($expectedResult, $rule->assert('my-string'));
    }

    public function testNoModelFactoryException()
    {
        $this->setExpectedException('\Exception');

        $rule = new MustNotExist('String\Model', 'foo');
        $rule->assert('bar');
    }

    public function baseProvider()
    {
        return [
            [['test'], false],
            [[], true],
        ];
    }

    private function getMockModelFactory()
    {
        return $this->getMockBuilder('AV\Model\ModelFactory')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMockModel($queryBuilder)
    {
        $mock = $this->getMockBuilder('AV\Model\Model')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('query')
            ->willReturn($queryBuilder);

        return $mock;
    }

    private function getMockQueryBuilder()
    {
        $mock = $this->getMockBuilder('AV\Model\QueryBuilder\QueryBuilderHandler')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('where')
            ->willReturn($mock);

        return $mock;
    }
}
 