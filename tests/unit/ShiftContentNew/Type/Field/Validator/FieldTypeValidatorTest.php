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

use ShiftContentNew\Type\Field\Validator\FieldTypeValidator;


/**
 * Field type validator test
 * This holds unit tests for field type validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldTypeValidatorTest extends TestCase
{


    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new FieldTypeValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Validator\FieldTypeValidator',
            $validator
        );
    }


    /**
     * Test that we can obtain validator from service locator
     * @test
     */
    public function canGetValidatorFromLocator()
    {
        $name = 'ShiftContentNew\Type\Field\Validator\FieldTypeValidator';
        $validator = $this->getLocator()->get($name);
        $this->assertInstanceOf($name, $validator);
        $this->assertNotNull($validator->getLocator());
    }


    /**
     * Test that we fail validation for field types that are not present
     * in configuration.
     * @test
     */
    public function failValidationForNotConfiguredFieldType()
    {
        $name = 'ShiftContentNew\Type\Field\Validator\FieldTypeValidator';
        $validator = $this->getLocator()->get($name);
        $this->assertFalse($validator->isValid('Not\Configured'));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['fieldTypeNotConfigured']));
    }


    /**
     * Test that configured but not existent field type class fails validation
     * @test
     */
    public function nonexistentFieldTypeFailsValidation()
    {
        $validatorName = 'Test\Field\Type';

        $factoryName = 'ShiftContentNew\Type\Field\FieldFactory';
        $factory = Mockery::mock($factoryName);
        $factory->shouldReceive('getFieldTypes')->andReturn(array(
            'test' => $validatorName
        ));

        $locator = Mockery::mock('Zend\Di\Di');
        $locator->shouldReceive('get')->with($factoryName)->andReturn($factory);

        $validator = new FieldTypeValidator;
        $validator->setLocator($locator);
        $this->assertFalse($validator->isValid($validatorName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['noFieldType']));

    }

    /**
     * Test that existing field type passes validation.
     * @test
     */
    public function existingFieldTypePassesValidation()
    {
        $name = 'ShiftContentNew\Type\Field\Validator\FieldTypeValidator';
        $validator = $this->getLocator()->get($name);
        $this->assertTrue($validator->isValid(
            'ShiftContentNew\FieldType\File\FileType'
        ));
    }


}//class ends here