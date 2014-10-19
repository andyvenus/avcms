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
    private $form_entity_processor;
    /**
     * @var Fixtures\StandardFormEntity
     */
    private $standard_entity;
    /**
     * @var String
     */
    private $form_data;

    public function setUp()
    {
        $this->form_entity_processor = new GetterSetterEntityProcessor();
        $this->standard_entity = new StandardFormEntity();

        $this->form_data = array(
            'name' => 'FormBlueprint name',
            'description' => 'FormBlueprint description',
            'published' => 0
        );
    }

    public function testGetFromEntity()
    {
        $entity = $this->standard_entity;
        $entity->setName('Dog');
        $entity->setDescription('Dogs are one of the oldest domesticated animals');
        $entity->setPublished(1);

        $extracted = $this->form_entity_processor->getFromEntity($entity, array('name', 'description', 'published', 'not_in_entity'));

        $expected = array('name' => 'Dog', 'description' => 'Dogs are one of the oldest domesticated animals', 'published' => 1);

        $this->assertEquals($expected, $extracted);
    }

    public function testSaveToEntity()
    {
        $this->form_entity_processor->saveToEntity($this->standard_entity, $this->form_data);

        $this->assertEquals($this->form_data['name'], $this->standard_entity->getName());
        $this->assertEquals($this->form_data['description'], $this->standard_entity->getDescription());
        $this->assertEquals($this->form_data['published'], $this->standard_entity->getPublished());
    }
}
 