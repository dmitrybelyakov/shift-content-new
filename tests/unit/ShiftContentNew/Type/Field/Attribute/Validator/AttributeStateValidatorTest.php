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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Attribute\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Attribute\Validator\AttributeStateValidator;
use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Type\Field\Attribute\AttributeOption;

/**
 * Attribute state validator test
 * This holds unit tests for attribute state validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeStateValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';

        $validator = new AttributeStateValidator;
        $this->assertInstanceOf($class, $validator);
    }


    /**
     * Test that we are able to get validator from service locator.
     * @test
     */
    public function canGetValidatorFromLocator()
    {
        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';

        $validator = $this->getLocator()->get($class);
        $this->assertInstanceOf($class, $validator);

        //assert locator injected
        $this->assertNotNull($validator->getLocator());
    }


    /**
     * Test that valid attribute passes validation.
     * test
     */
    public function validAttributePassesValidation()
    {
        //get factory
        $factory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $factory = $this->getLocator()->get($factory);

        //use factory to create valid attribute
        $validators = $factory->getValidatorTypes();
        $type = array_shift($validators);
        $attribute = $factory->createValidator($type['class']);

        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';
        $validator = $this->getLocator()->get($class);
        $this->assertTrue($validator->isValid($attribute));
    }


    /**
     * Test that attribute without a class fails validation
     * @test
     */
    public function missingAttributeClassFailsValidation()
    {
        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';
        $validator = $this->getLocator()->get($class);

        $this->assertFalse($validator->isValid(new Attribute));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['attributeMisconfigured']));
    }


    /**
     * Test that class name not configured as field filter or validator
     * fails validation
     * @test
     */
    public function notConfiguredAttributeClassFailsValidation()
    {
        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';
        $validator = $this->getLocator()->get($class);

        $attribute = new Attribute;
        $attribute->setClassName('Exception');
        $this->assertFalse($validator->isValid($attribute));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['noConfigForClass']));
    }


    /**
     * Test that an attribute with extra options fail validation
     * @test
     */
    public function attributeWithExtraOptionsFailValidation()
    {
        //get factory
        $factory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $factory = $this->getLocator()->get($factory);

        //use factory to create valid attribute
        $validators = $factory->getValidatorTypes();
        $type = array_shift($validators);
        $attribute = $factory->createValidatorByClassName($type['class']);

        //add an extra option
        $option = new AttributeOption(array(
            'name' => 'test',
            'variable' => 'test',
            'type' => 'string'
        ));
        $attribute->addOption($option);

        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';
        $validator = $this->getLocator()->get($class);
        $this->assertFalse($validator->isValid($attribute));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['optionsMisconfigured']));
    }


    /**
     * Test that attribute with misconfigured options fail validation.
     * @test
     */
    public function attributeWithMisconfiguredOptionsFailValidation()
    {
        //get factory
        $factory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $factory = $this->getLocator()->get($factory);

        //use factory to create valid attribute
        $validators = $factory->getValidatorTypes();
        $type = array_shift($validators);
        $attribute = $factory->createValidatorByClassName($type['class']);

        //set bad option type
        $attribute->getOptionByVariableName('allowWhitespace')
            ->setType('string');

        $class = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $class .= '\AttributeStateValidator';
        $validator = $this->getLocator()->get($class);
        $this->assertFalse($validator->isValid($attribute));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['optionsMisconfigured']));
    }



}//class ends here