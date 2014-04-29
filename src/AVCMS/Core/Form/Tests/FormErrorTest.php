<?php
/**
 * User: Andy
 * Date: 31/03/2014
 * Time: 13:35
 */

namespace AVCMS\Core\Form\Tests;

use AVCMS\Core\Form\FormError;

class FormErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testFormError()
    {
        $form_error = new FormError('param', 'Error Message', true, array('test'));

        $this->assertEquals('param', $form_error->getParam());
        $this->assertEquals('Error Message', $form_error->getMessage());
        $this->assertTrue($form_error->getTranslate());
        $this->assertEquals(array('test'), $form_error->getTranslationParams());
    }

    public function testSetters()
    {
        $form_error = new FormError(null, null, null, null);

        $form_error->setParam('param');
        $form_error->setMessage('Error Message');
        $form_error->setTranslate(true);
        $form_error->setTranslationParams(array('test'));

        $this->assertNotNull($form_error->getParam());
        $this->assertNotNull($form_error->getMessage());
        $this->assertNotNull($form_error->getTranslate());
        $this->assertNotNull($form_error->getTranslationParams());
    }
}
 