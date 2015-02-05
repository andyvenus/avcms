<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 20:30
 */

namespace AV\Form\Tests;


use AV\Form\EntityProcessor\GetterSetterEntityProcessor;
use AV\Form\Tests\Fixtures\StandardFormEntity;

class FormEntityProcessorTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var GetterSetterEntityProcessor
     */
    private $formEntityProcessor;
    /**
     * @var Fixtures\StandardFormEntity
     */
    private $standardEntity;
    /**
     * @var array
     */
    private $formData;

    public function setUp()
    {
        $this->formEntityProcessor = new GetterSetterEntityProcessor();
        $this->standardEntity = new StandardFormEntity();

        $this->formData = array(
            'name' => 'FormBlueprint name',
            'description' => 'FormBlueprint description',
            'published' => 0
        );
    }

    public function testGetFromEntity()
    {
        $entity = $this->standardEntity;
        $entity->setName('Dog');
        $entity->setDescription('Dogs are one of the oldest domesticated animals');
        $entity->setPublished(1);

        $extracted = $this->formEntityProcessor->getFromEntity($entity, array('name', 'description', 'published', 'not_in_entity'));

        $expected = array('name' => 'Dog', 'description' => 'Dogs are one of the oldest domesticated animals', 'published' => 1);

        $this->assertEquals($expected, $extracted);
    }

    public function testSaveToEntity()
    {
        $this->formEntityProcessor->saveToEntity($this->standardEntity, $this->formData);

        $this->assertEquals($this->formData['name'], $this->standardEntity->getName());
        $this->assertEquals($this->formData['description'], $this->standardEntity->getDescription());
        $this->assertEquals($this->formData['published'], $this->standardEntity->getPublished());
    }
}
