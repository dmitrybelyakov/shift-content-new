<?php
/**
 * Projectshift
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file license/projectshift.mit.txt
 * It is also available through the world-wide-web at this URL:
 * http://projectshift.eu/license/mit
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@projectshift.eu so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010 Webcomplex LLC (http://www.projectshift.eu)
 * @license    http://projectshift.eu/license/mit     MIT License
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 */

/**
 * @namespace
 */
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Validator\FieldSettingsValidator;


/**
 * Field settings validator test
 * This holds unit tests for field settings validator. Here we will test
 * how it does validation delegation to specific settings validators based on
 * field types.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldSettingsValidatorTest extends TestCase
{
    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new FieldSettingsValidator($this->sm());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Validator\FieldSettingsValidator',
            $validator
        );
    }

    /**
     * Test that we can inject arbitrary field type factory.
     * @test
     */
    public function canInjectFieldTypeFactory()
    {
        $factory = Mockery::mock('ShiftContentNew\FieldType\FieldTypeFactory');
        $validator = new FieldSettingsValidator($this->sm());
        $validator->setFieldTypeFactory($factory);
        $this->assertEquals($factory, $validator->getFieldTypeFactory());
    }


    /**
     * Test that we are able to obtain field type factory from locator
     * if it was not injected.
     * @test
     */
    public function getFieldTypeFactoryFromLocatorIfNoneInjected()
    {
        $validator = new FieldSettingsValidator($this->sm());
        $factory = $validator->getFieldTypeFactory();
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\FieldTypeFactory',
            $factory
        );
    }


    /**
     * Test that we do throw an exception when validating settings without
     * parent field context.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Parent field not found.
     */
    public function throwExceptionIfValidatingWithoutFieldContext()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);

        $settings = $fieldFactory->createField('file')->getSettings();
        $validator = new FieldSettingsValidator($this->sm());
        $validator->validate($settings);
    }


    /**
     * Test that we silently pass if field has no type.
     * @test
     */
    public function silentlyPassIfFieldHasNoType()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);
        $field = $fieldFactory->createField('file');
        $field->setFieldType(null);

        $validator = new FieldSettingsValidator($this->sm());
        $result = $validator->validate(null, $field);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we pass validation if field type has no settings configured.
     * @test
     */
    public function passValidationIfFieldTypeHasNoSettings()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);
        $field = $fieldFactory->createField('string');

        $validator = new FieldSettingsValidator($this->sm());
        $result = $validator->validate(null, $field);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we fail validation if we have no settings but should have.
     * @test
     */
    public function failValidationIfSettingsAreMissing()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);
        $field = $fieldFactory->createField('file');

        $validator = new FieldSettingsValidator($this->sm());
        $result = $validator->validate(null, $field);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['_notEmpty']));
    }


    /**
     * Test that we fail validation if settings are invalid.
     * @test
     */
    public function failIfSettingsAreInvalid()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);

        $field = $fieldFactory->createField('file');
        $settings = $field->getSettings();

        $validator = new FieldSettingsValidator($this->sm());
        $result = $validator->validate($settings, $field);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['destination']));
    }


    /**
     * Test that we are able to delegate validation and pass if everything is
     * correct.
     * @test
     */
    public function canValidateSettingsAndPass()
    {
        $fieldFactory = 'ShiftContentNew\Type\Field\FieldFactory';
        $fieldFactory = $this->sm()->get($fieldFactory);

        $field = $fieldFactory->createField('file');
        $settings = $field->getSettings();
        $settings->setDestination('data/temp');

        $validator = new FieldSettingsValidator($this->sm());
        $result = $validator->validate($settings, $field);
        $this->assertTrue($result->isValid());
    }


}//class ends here