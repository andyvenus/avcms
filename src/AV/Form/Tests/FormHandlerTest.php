<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 11:12
 */

namespace AV\Form\Tests;

use AV\Form\EntityProcessor\GetterSetterEntityProcessor;
use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AV\Form\FormHandler;
use AV\Form\FormView;
use AV\Form\RequestHandler\SymfonyRequestHandler;
use AV\Form\Tests\Fixtures\AvcmsStandardEntity;
use AV\Form\Tests\Fixtures\BasicForm;
use AV\Form\Tests\Fixtures\StandardFormEntity;
use AV\Form\Tests\Fixtures\StandardForm;
use AV\Form\Type\TypeHandler;
use Symfony\Component\HttpFoundation\Request;

class FormHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasicForm
     */
    protected $basic_form;

    /**
     * @var StandardForm
     */
    protected $standard_form;

    /**
     * @var array
     */
    protected $standard_form_request;

    /**
     * @var array
     */
    protected $basic_form_request;

    /**
     * @var FormHandler
     */
    protected $standard_form_handler;

    /**
     * @var FormHandler
     */
    protected $basic_form_handler;

    /**
     * @var \AV\Form\ValidatorExtension\ValidatorExtension|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mock_validator;

    /**
     * @var array The original $_GET, $_POST & $_FILES vars, reset for each test
     */
    protected $default_request;

    public function setUp()
    {
        $this->basic_form = new BasicForm();
        $this->standard_form = new StandardForm();
        
        $this->basic_form_handler = new FormHandler($this->basic_form, null, new GetterSetterEntityProcessor());
        $this->standard_form_handler = new FormHandler($this->standard_form);

        $this->basic_form_request = array(
            'name' => 'Example Name'
        );

        $this->standard_form_request = array(
            'name' => 'Example Name',
            'description' => 'Example Description',
            'category' => '3',
            'password' => '',
            'published' => 1
        );

        $this->mock_validator = $this->getMock('AV\Form\ValidatorExtension\ValidatorExtension');

        $this->resetRequest();
    }

    public function testSetGetFormAttributes()
    {
        $this->assertEquals('POST', $this->basic_form_handler->getMethod());
        $this->assertEquals(null, $this->basic_form_handler->getAction());

        $this->basic_form_handler->setMethod('GET');
        $this->basic_form_handler->setAction('example_url/something');

        $this->assertEquals('GET', $this->basic_form_handler->getMethod());
        $this->assertEquals('example_url/something', $this->basic_form_handler->getAction());
    }

    public function testInvalidMethod()
    {
        $this->setExpectedException('\Exception');

        $this->basic_form_handler->setMethod('BAD');
    }

    public function testHandleSymfonyPostRequest()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped("Symfony Request component not installed");

            return null;
        }

        $request = new Request(array(), array(
            'name' => 'Example Name'
        ));

        $form_handler = new FormHandler($this->basic_form, new SymfonyRequestHandler());

        $form_handler->setMethod('POST');

        $form_handler->handleRequest($request);

        $this->assertEquals('Example Name', $form_handler->getData('name'));
        $this->assertTrue($form_handler->isSubmitted());
    }

    public function testHandleSymfonyGetRequest()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped("Symfony Request component not installed");

            return null;
        }

        $request = new Request(array(
            'name' => 'Example Name'
        ));

        $form_handler = new FormHandler($this->basic_form, new SymfonyRequestHandler());

        $form_handler->setMethod('GET');

        $form_handler->handleRequest($request, 'symfony');

        $this->assertEquals('Example Name', $form_handler->getData('name'));
        $this->assertTrue($form_handler->isSubmitted());
    }

    public function testHandleStandardRequest()
    {
        $_POST = $this->standard_form_request;

        $form_handler = $this->standard_form_handler;

        $form_handler->handleRequest();

        $this->assertEquals('Example Name', $form_handler->getData('name'));
        $this->assertTrue($form_handler->isSubmitted());
    }

    public function testIncompleteStandardRequest()
    {
        $_POST = $this->standard_form_request;

        unset($_POST['password']);

        $form_handler = $this->standard_form_handler;

        $form_handler->handleRequest();

        $this->assertEquals(null, $form_handler->getData('password'));
        $this->assertFalse($form_handler->isSubmitted());
    }

    /**
     * @dataProvider providerDefaultValues
     */
    public function testDefaultValues($default_values)
    {
        $form_handler = $this->standard_form_handler;
        $form_handler->setDefaultValues($default_values);

        foreach ($default_values as $name => $value) {
            $this->assertEquals($default_values[$name], $form_handler->getData($name));
        }
    }

    public function testGetAllData()
    {
        $_POST = array('description'=>'Default description', 'published' => '1');

        $this->standard_form_handler->handleRequest();

        $form_data = $this->standard_form_handler->getData();

        $this->assertEquals($_POST, $form_data);
    }

    public function testAvcmsEntity()
    {
        if (!class_exists('AV\Model\Entity')) {
            $this->markTestSkipped("AVCMS Not installed");

            return null;
        }

        $entity = new AvcmsStandardEntity();
        $entity->setName('AVCMS Name');
        $entity->setDescription('AVCMS Description');

        $form_handler = $this->standard_form_handler;
        $form_handler->bindEntity($entity);

        $form_handler->handleRequest(array(), 'standard');

        $this->assertEquals('AVCMS Name', $form_handler->getData('name'));
        $this->assertEquals('AVCMS Description', $form_handler->getData('description'));
    }

    public function testMultipleEntityAssignment()
    {
        $entity_one = new StandardFormEntity();
        $entity_one->setName('Name One');
        $entity_one->setDescription('Description One');
        $entity_one->setCategory('5');
        $entity_one->setPassword('Secure One');

        $entity_two = new StandardFormEntity();
        $entity_two->setName('Name Two');
        $entity_two->setDescription('Description Two');
        $entity_two->setCategory('1');
        $entity_two->setPassword('Secure Two');

        $this->standard_form_handler->bindEntity($entity_one, array('name', 'description'));
        $this->standard_form_handler->bindEntity($entity_two, array('name', 'password', 'category'));

        $this->assertEquals(2, count($this->standard_form_handler->getEntities()));

        $name_field = $this->standard_form_handler->getField('name');
        $this->assertEquals('Name Two', $name_field['value']);

        $description_field = $this->standard_form_handler->getField('description');
        $this->assertEquals('Description One', $description_field['value']);

        $_POST = $this->standard_form_request;
        $this->standard_form_handler->handleRequest();

        $this->standard_form_handler->saveToEntities();

        $this->assertEquals('Example Name', $entity_one->getName());
        $this->assertEquals('Example Description', $entity_one->getDescription());
        $this->assertEquals('5', $entity_one->getCategory());
        $this->assertEquals('Secure One', $entity_one->getPassword());

        $this->assertEquals('Example Name', $entity_two->getName());
        $this->assertEquals('Description Two', $entity_two->getDescription());
        $this->assertEquals('3', $entity_two->getCategory());
        $this->assertEquals('', $entity_two->getPassword());
    }

    public function testSaveToAndGetClonedEntities()
    {
        $entity = new StandardFormEntity();
        $entity->setName('Name One');
        $entity->setDescription('Description One');
        $entity->setCategory('5');
        $entity->setPassword('Secure One');

        $this->standard_form_handler->bindEntity($entity);

        $_POST = $this->standard_form_request;
        $this->standard_form_handler->handleRequest();

        $clones = $this->standard_form_handler->saveToAndGetClonedEntities();

        $this->assertEquals('Name One', $entity->getName());
        $this->assertEquals('Example Name', $clones[0]['entity']->getName());
    }

    public function testBindEntityException()
    {
        $this->setExpectedException('\Exception');

        $this->basic_form_handler->handleRequest(array());

        $this->basic_form_handler->bindEntity(new StandardFormEntity());
    }

    /**
     * @dataProvider providerDefaultValues
     *
     * Test that these values take precedence over each other
     * Request > Entity Values > Default Values > Internal FormBlueprint Default Values
     */
    public function testValuePriority($default_values)
    {
        $form_handler = $this->standard_form_handler;

        $this->assertEquals('Default description', $form_handler->getData('description'));

        $form_handler->setDefaultValues($default_values);

        $this->assertEquals($default_values['description'], $form_handler->getData('description'));

        $form_handler->bindEntity(new StandardFormEntity());

        $this->assertEquals("Entity Description", $form_handler->getData('description'));

        $form_handler->handleRequest($this->standard_form_request, 'standard');

        $this->assertEquals("Example Description", $form_handler->getData('description'));
    }

    public function testGetField()
    {
        $request = $this->standard_form_request;

        $form_handler = $this->basic_form_handler;

        $form_handler->handleRequest($request, 'standard');

        $expected = array (
            'name' => 'name',
            'type' => 'text',
            'options' => array (
                'label' => 'Name',
                'required' => true
            ),
            'value' => 'Example Name',
            'has_error' => ''
        );

        $this->assertEquals($expected, $form_handler->getField('name'));
    }

    public function testGetProcessedFields()
    {
        $request = $this->standard_form_request;

        $form_handler = $this->basic_form_handler;

        $form_handler->handleRequest($request, 'standard');

        $expected = array(
            'name' => array (
                'name' => 'name',
                'type' => 'text',
                'options' => array (
                    'label' => 'Name',
                    'required' => true
                ),
                'value' => 'Example Name',
                'has_error' => ''
            )
        );

        $this->assertEquals($expected, $form_handler->getProcessedFields());
    }

    public function testNonExistentProcessedField()
    {
        $this->assertFalse($this->basic_form_handler->getProcessedField('does-not-exist'));
    }

    public function testFieldHasError()
    {
        $this->basic_form_handler->handleRequest();

        $custom_error = new FormError('name', 'Name field has an error');

        $this->basic_form_handler->addCustomErrors(array($custom_error));

        $this->assertTrue($this->basic_form_handler->fieldHasError('name'));
    }

    public function testFieldHasValidatorError()
    {
        $this->mock_validator->expects($this->any())
            ->method('fieldHasError')
            ->will($this->returnValue(true));

        $this->basic_form_handler->setValidator($this->mock_validator);
        $this->basic_form_handler->handleRequest();

        $this->assertTrue($this->basic_form_handler->fieldHasError('name'));
    }

    public function testFieldRequired()
    {
        $this->basic_form->add('no_label', 'text', array('required' => true));
        $form = new FormHandler($this->basic_form);

        $_POST['name'] = '';
        $_POST['no_label'] = '';

        $form->handleRequest();

        $this->assertFalse($form->isValid());
        $this->assertCount(2, $form->getValidationErrors());
    }

    public function testArrayFieldRequired()
    {
        $this->basic_form->add('no_label[example]', 'text', array('required' => true));
        $form = new FormHandler($this->basic_form);

        $_POST['name'] = '';
        $_POST['no_label']['example'] = '';

        $form->handleRequest();

        $this->assertFalse($form->isValid());
        $this->assertCount(2, $form->getValidationErrors());
    }

    public function testHasFieldWithName()
    {
        $this->assertTrue($this->basic_form_handler->hasFieldWithName('name'));
        $this->assertFalse($this->basic_form_handler->hasFieldWithName('random_name'));
    }

    public function testSetData()
    {
        $this->basic_form_handler->setData('name', 'example-data');

        $this->assertEquals('example-data', $this->basic_form_handler->getData('name'));
    }

    public function testCreateView()
    {
        $form_handler = $this->basic_form_handler;

        $mock_validator = $this->mock_validator;
        $mock_validator->expects($this->any())
            ->method('getErrors')
            ->will($this->returnValue($errors = array(new FormError('name', 'Error One'))));

        $form_handler->setValidator($mock_validator);

        $_POST = $this->basic_form_request;
        $form_handler->handleRequest();

        $form_view = $form_handler->createView();

        $this->assertTrue(isset($form_view->name));
        $this->assertEquals($errors, $form_view->getErrors());
    }

    public function testSetFormView()
    {
        $form_view = new FormView();

        $this->basic_form_handler->setFormView($form_view);

        $this->basic_form_handler->createView();

        $this->assertEquals('name', $form_view->name['name']);
    }

    public function testGetSetMethod()
    {
        $form_handler = $this->basic_form_handler;

        $this->assertEquals('POST', $form_handler->getMethod());

        $form_handler->setMethod('GET');

        $this->assertEquals('GET', $form_handler->getMethod());
    }

    public function testBasicValidation()
    {
        $this->mock_validator->expects($this->any())
            ->method('getErrors')
            ->will($this->returnValue($errors = array('error_one' => 'Error One Message')));
        $this->mock_validator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->basic_form_handler->setValidator($this->mock_validator);

        $this->basic_form_handler->handleRequest($this->basic_form_request);

        $this->assertEquals($this->mock_validator, $this->basic_form_handler->getValidator());
        $this->assertFalse($this->basic_form_handler->isValid());
        $this->assertEquals($errors, $this->basic_form_handler->getValidationErrors());
    }

    public function testNoValidator()
    {
        $this->assertFalse($this->basic_form_handler->isValid());

        $this->setExpectedException('\Exception', 'Cannot get validator, no validator assigned');
        $this->basic_form_handler->getValidator();
    }

    public function testValidForm()
    {
        $form = $this->basic_form_handler;
        $_POST['name'] = 'valid';
        $form->handleRequest();

        $this->assertTrue($form->isValid());
    }

    public function testArrayFields()
    {
        $form = new FormBlueprint();
        $form->add('colours[]', 'text', array(
            'default' => '#FF0000'
        ));

        $form->add('colours[]', 'text', array(
            'default' => '#00FF00'
        ));

        $form->add('colours[]', 'text', array(
            'default' => '#0000FF'
        ));

        $form_handler = new FormHandler($form);

        $data = $form_handler->getData('colours');

        $this->assertEquals('#0000FF', $data[2]);

        $form_view = $form_handler->createView();

        $this->assertEquals(3, count($form_view->colours['fields']));
        $this->assertEquals('colours[]', $form_view->colours['fields'][0]['name']);
        $this->assertEquals('colours[]', $form_view->colours['fields'][2]['name']);
    }

    public function testNamedArrayFields()
    {
        $form = new FormBlueprint();
        $form->add('colours[red]', 'text', array(
            'default' => '#FF0000'
        ));

        $form->add('colours[green]', 'text', array(
            'default' => '#00FF00'
        ));

        $form->add('colours[blue]', 'text', array(
            'default' => '#0000FF'
        ));

        $form_handler = new FormHandler($form);

        $data = $form_handler->getData('colours');

        $this->assertEquals('#0000FF', $data['blue']);

        $form_view = $form_handler->createView();

        $this->assertEquals(3, count($form_view->colours['fields']));
        $this->assertEquals('colours[red]', $form_view->colours['fields']['red']['name']);
        $this->assertEquals('colours[blue]', $form_view->colours['fields']['blue']['name']);
    }

    public function testGetFormBlueprint()
    {
        $form = $this->standard_form_handler->getFormBlueprint();

        $this->assertEquals($this->standard_form, $form);
    }

    public function testSetTypeHandler()
    {
        $type_handler = new TypeHandler();
        $form_handler = new FormHandler($this->basic_form, null, null, $type_handler);
        $this->assertEquals($type_handler, $form_handler->getTypeHandler());
    }

    public function testAllowUnset()
    {
        $form = new FormBlueprint();
        $form->add('unset_allowed', 'input', array(
            'allow_unset' => true,
            'unset_value' => 'unset_value'
        ));

        $handler = new FormHandler($form);

        $handler->handleRequest();

        $this->assertTrue($handler->isSubmitted());

        $processed_field = $handler->getProcessedField('unset_allowed');

        $this->assertEquals('unset_value', $processed_field['value']);
    }

    public function testCustomErrors()
    {
        $custom_error = new FormError('name', 'Custom Error');

        $this->basic_form_handler->addCustomErrors(array($custom_error));
    }

    public function testCustomErrorsException()
    {
        $this->setExpectedException('\Exception', 'Custom errors must be AV\Form\FormError objects');

        $this->basic_form_handler->addCustomErrors(array('invalid'));
    }

    public function testGetEntityProcessor()
    {
        $ep = $this->basic_form_handler->getEntityProcessor();

        $this->assertInstanceOf('AV\Form\EntityProcessor\GetterSetterEntityProcessor', $ep);
    }

    public function testDataTransform()
    {
        $blueprint = new FormBlueprint();
        $blueprint->add('name', 'text', array('transform' => 'example'));
        $form = new FormHandler($blueprint);

        $transformer = $this->getMock('AV\Form\Transformer\TransformerManager');
        $transformer->expects($this->any())
            ->method('fromForm')
            ->will($this->returnValue('transformed'));

        $form->setTransformerManager($transformer);

        $_POST = $this->basic_form_request;

        $form->handleRequest();

        $this->assertEquals('transformed', $form->getData('name'));
    }

    public function testFileFormEncoding()
    {
        $form_blueprint = new FormBlueprint();
        $form_blueprint->add('file_upload', 'file');

        $handler = new FormHandler($form_blueprint);

        $this->assertEquals('multipart/form-data', $handler->getEncoding());
    }

    public function testFormEvents()
    {
        if (!class_exists('\Symfony\Component\EventDispatcher\EventDispatcher')) {
            $this->markTestSkipped('Symfony Event dispatcher not installed');
            return;
        }

        $event_dispatcher_mock = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $event_dispatcher_mock->expects($this->exactly(2))
            ->method('dispatch');

        // Triggers FormHandlerConstructEvent
        $handler = new FormHandler(new BasicForm(), null, null, null,  $event_dispatcher_mock);
        // Triggers FormHandlerRequestEvent
        $handler->handleRequest();
    }

    public function testMergeData()
    {
        $this->basic_form_handler->setData('one', 1);

        $this->basic_form_handler->mergeData(['two' => 2]);

        $this->assertEquals(['one' => 1, 'two' => 2], $this->basic_form_handler->getData());
    }

    public function providerDefaultValues()
    {
        return array(
            array(
                array(
                    'name' => 'Example name',
                    'description' => 'Example description',
                    'published' => 2
                )
            )
        );
    }

    protected function resetRequest()
    {
        if (!isset($this->default_request)) {
            $this->default_request['GET'] = $_GET;
            $this->default_request['POST'] = $_POST;
            $this->default_request['FILES'] = $_FILES;
        }

        $_GET = $this->default_request['GET'];
        $_POST = $this->default_request['POST'];
        $_FILES = $this->default_request['FILES'];
    }
}
 