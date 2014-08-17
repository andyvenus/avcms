<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 14:30
 */

namespace AVCMS\Core\Model\Tests;


use AVCMS\Core\Model\Tests\Fixtures\Animal;
use AVCMS\Core\Model\Tests\Fixtures\Food;

class ModelTest extends ModelTestCase
{
    /**
     * @var \AVCMS\Core\Model\Model
     */
    private $model;

    public function setUp()
    {
        parent::setUp();

        $this->model = $this->model_factory->create('AVCMS\Core\Model\Tests\Fixtures\Animals');
    }

    public function testFetchOne()
    {
        $entity = $this->model->find(1)->first();

        $this->assertEquals("Lion", $entity->getName());

        $entity_two = $this->model->getOne(2);

        $this->assertEquals("Monkey", $entity_two->getName());
    }

    public function testInsert()
    {
        $entity = new Animal();
        $entity->setName("Zebra");
        $entity->setDescription("Known for their black and white stripes");
        $entity->setFoodItemId(1);

        $this->model->save($entity);

        $expected_dataset = new ArrayDataSet(
            array(
                'animals' => array(
                    array ('id' => '3', 'name' => $entity->getName(), 'description' => $entity->getDescription(), 'views' => '0', 'food_item_id' => $entity->getFoodItemId())
                )
            )
        );

        $queryTable = $this->getConnection()->createQueryTable(
            'animals', 'SELECT * FROM animals WHERE id = 3'
        );

        $this->assertTablesEqual($expected_dataset->getTable('animals'), $queryTable);
    }

    public function testUpdate()
    {
        $entity = new Animal();
        $entity->setId(1);
        $entity->setViews(11);

        $this->model->save($entity);

        $expected_dataset = new ArrayDataSet(
            array(
                'animals' => array(
                    array ('id' => '1', 'name' => 'Lion', 'description' => 'Lions are one of many big cats. They are known to go "roar"!', 'views' => '11', 'food_item_id' => 3)
                )
            )
        );

        $queryTable = $this->getConnection()->createQueryTable(
            'animals', 'SELECT * FROM animals WHERE id = 1'
        );

        $this->assertTablesEqual($expected_dataset->getTable('animals'), $queryTable);
    }

    public function testDelete()
    {
        $entity = new Animal();
        $entity->setId(1);

        $this->model->delete($entity);

        $this->assertEquals(1, $this->getConnection()->getRowCount('animals'));
    }

    public function testJoin()
    {

        $food = $this->model_factory->create('AVCMS\Core\Model\Tests\Fixtures\Food');

        $animal = $this->model->findOne(1)->modelJoin($food, array('name'))->first();

        $this->assertEquals('Meat', $animal->food_item->getName());
    }

    protected function getDataSet()
    {
        return $this->createXmlDataSet(dirname(__FILE__).'/data/test_data.xml');
    }

    public function testOneOrNew()
    {
        $exists = $this->model->getOneOrNew(1);

        $this->assertEquals(1, $exists->getId());

        $new = $this->model->getOneOrNew(null);

        $this->assertNull($new->getId());

        $not_exists = $this->model->getOneOrNew(12345677);

        $this->assertNull($not_exists);
    }
}
 