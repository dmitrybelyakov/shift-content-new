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

use ShiftContentNew\Type\Field\Validator\FieldStateValidator;
use ShiftContentNew\Type\Field\FieldFactory;


/**
 * Field state validator test
 * This holds unit tests for field state validator that checks that field is
 * properly assembled from configuration.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldStateValidatorTest extends TestCase
{
    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new FieldStateValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Validator\FieldStateValidator',
            $validator
        );
    }


    /**
     * Test that we throw an exception when validating field of invalid type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field must be of ShiftContentNew
     */
    public function throwExceptionWhenValidatingFieldOfBadType()
    {
        $field = array();
        $validator = new FieldStateValidator;
        $validator->isValid($field);
    }


    /**
     * Test that we can validate field state and pass if everything is valid.
     * @test
     */
    public function canValidateFieldStateAndPass()
    {
        $factory = new FieldFactory($this->getLocator());
        $types = $factory->getFieldTypes();
        $field = $factory->createFieldOfType($types['file']);

        $validator = 'ShiftContentNew\Type\Field\Validator\FieldStateValidator';
        $validator = $this->getLocator()->get($validator);

        $this->assertTrue($validator->isValid($field));
    }


    /**
     * Test that we fail validation if field type is missing or invalid.
     * @test
     */
    public function failValidationIfFieldMissesType()
    {
        $factory = new FieldFactory($this->getLocator());
        $types = $factory->getFieldTypes();
        $field = $factory->createFieldOfType($types['file']);
        $field->setFieldType(null);

        $validator = 'ShiftContentNew\Type\Field\Validator\FieldStateValidator';
        $validator = $this->getLocator()->get($validator);
        $this->assertFalse($validator->isValid($field));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['fieldMisconfigured']));
    }


    /**
     * Test that we fail validation if field settings configured but missing
     * or invalid
     * @test
     */
    public function failValidationIfFieldSettingsMisconfigured()
    {
        $factory = new FieldFactory($this->getLocator());
        $types = $factory->getFieldTypes();
        $field = $factory->createFieldOfType($types['file']);
        $field->setSettings(null);

        $validator = 'ShiftContentNew\Type\Field\Validator\FieldStateValidator';
        $validator = $this->getLocator()->get($validator);
        $this->assertFalse($validator->isValid($field));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['fieldSettingsMisconfigured']));
    }

}//class ends here