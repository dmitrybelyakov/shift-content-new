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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Attribute;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Type\Field\Attribute\AttributeOption as Option;

/**
 * Attribute test
 * This holds unit tests for content type field attributes.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeTest extends TestCase
{

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate attribute.
     * @test
     */
    public function canInstantiateOption()
    {
        $attribute = new Attribute;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\Attribute',
            $attribute
        );
    }


    /**
     * Test that we are able to populate attribute from array at instantiation.
     * @test
     */
    public function canPopulateFromArrayAtConstruction()
    {
        $data = array('type' => 'filter');
        $attribute = new Attribute($data);
        $this->assertEquals('filter', $attribute->getType());
    }


    /**
     * Test that we can get an array representation of attribute
     * @test
     */
    public function canGetArrayRepresentationOfAttribute()
    {
        $attribute = new Attribute(array(
            'type' => 'filter',
            'className' => 'Zend\Filter\StringTrim'
        ));

        $attribute->addOption(new Option(array(
            'name' => 'Allow whitepaces',
            'variable' => 'allowWhitespace',
            'type' => 'bool',
        )));

        $attributeArray = $attribute->toArray();
        $this->assertTrue(is_array($attributeArray));
        $this->assertFalse(empty($attributeArray['options']));

    }


    /**
     * Test that we can use accessor to get an id.
     * @test
     */
    public function canGetId()
    {
        $attribute = new Attribute;
        $this->assertNull($attribute->getId());
    }


    /**
     * Test that we throw proper exception when setting invalid attribute type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Attribute must be either filter or validator
     */
    public function throwExceptionWhenSettingBadType()
    {
        $attribute = new Attribute();
        $attribute->setType('BAD!');
    }


    /**
     * Test that we are able to set attribute type.
     * @test
     */
    public function canSetAttributeType()
    {
        $type = 'validator';
        $attribute = new Attribute;
        $attribute->setType($type);
        $this->assertEquals($type, $attribute->getType());
    }


    /**
     * Test that we throw an exception when trying to set a nonexistent
     * class name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage No such class 'No\Class'
     */
    public function throwExceptionWhenSettingNonexistentClassName()
    {
        $attribute = new Attribute;
        $attribute->setClassName('No\Class');
    }


    /**
     * Test that we can set attribute class name
     * @test
     */
    public function canSetClassName()
    {
        $class = 'Zend\Filter\StringTrim';
        $attribute = new Attribute;
        $attribute->setClassName($class);
        $this->assertEquals($class, $attribute->getClassName());
    }


    /**
     * Test that we are able to set parent content field.
     * @test
     */
    public function canSetField()
    {
        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $attribute = new Attribute;
        $attribute->setField($field);
        $this->assertEquals($field, $attribute->getField());
    }



    /**
     * Test that we throw an exception when adding an option without a name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Option must have a name
     */
    public function throwExceptionWhenAddingAnOptionWithoutName()
    {
        $option = new Option;
        $option->setVariable('test');
        $option->setType('bool');

        $attribute = new Attribute;
        $attribute->addOption($option);
    }


    /**
     * Test that we throw an exception when adding an option without a variable
     * name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Option must have a variable name
     */
    public function throwExceptionWhenAddingAnOptionWithoutVariableName()
    {
        $option = new Option;
        $option->setName('Test');
        $option->setType('bool');

        $attribute = new Attribute;
        $attribute->addOption($option);
    }


    /**
     * Test that we throw an exception when adding an option without variable
     * type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Option must have a type
     */
    public function throwExceptionWhenAddingAnOptionWithoutType()
    {
        $option = new Option;
        $option->setName('Test');
        $option->setVariable('test');

        $attribute = new Attribute;
        $attribute->addOption($option);
    }


    /**
     * Test that we are able to add an option to attribute.
     * @test
     */
    public function canAddOption()
    {
        $option = new Option;
        $option->setName('Test');
        $option->setVariable('test');
        $option->setType('string');

        $attribute = new Attribute;
        $attribute->addOption($option);

        $options = $attribute->getOptions();
        $this->assertTrue(is_array($options));
        $this->assertFalse(empty($options));
    }


    /**
     * Test that we are able to remove option from attribute.
     * @test
     */
    public function canRemoveOption()
    {
        $attribute = new Attribute;

        $option = new Option;
        $option->setName('Test');
        $option->setVariable('test');
        $option->setType('string');

        //add first
        $attribute->addOption($option);
        $options = $attribute->getOptions();
        $this->assertFalse(empty($options));

        //now remove
        $attribute->removeOption($option);
        $options = $attribute->getOptions();
        $this->assertTrue(empty($options));
    }


    /**
     * Test that we are able to get option values.
     * @test
     */
    public function canGetOptionValues()
    {
        $option = new Option;
        $option->setName('Test');
        $option->setVariable('test');
        $option->setType('string');
        $option->setValue('some string value');

        $attribute = new Attribute;
        $attribute->addOption($option);

        $values = $attribute->getOptionValues();
        $this->assertTrue(array_key_exists('test', $values));
        $this->assertEquals($option->getValue(), $values['test']);
    }


    /**
     * Test that we are bale to get an option by its variable name
     * @test
     */
    public function canGetOptionByVariableName()
    {
        $option = new Option;
        $option->setName('Test');
        $option->setVariable('test');
        $option->setType('string');
        $option->setValue('some string value');

        $attribute = new Attribute;
        $attribute->addOption($option);

        $this->assertEquals(
            $option,
            $attribute->getOptionByVariableName('test')
        );
    }


    /**
     * Test that we can use magic overloading to get attribute option values.
     * @test
     */
    public function canUseMagicGettersOnOptions()
    {
        $data = array(
            'name' => 'Test',
            'variable' => 'test',
            'type' => 'string',
            'value' => 'some string value',
        );

        $attribute = new Attribute;
        $attribute->addOption(new Option($data));

        $this->assertEquals($data['value'], $attribute->getTest());
    }


    /**
     * Test that we are able to use magic overloading to set attribute option
     * values.
     * @test
     */
    public function canUseMagicSettersOnOptions()
    {
        $data = array(
            'name' => 'Test',
            'variable' => 'test',
            'type' => 'string',
        );

        $attribute = new Attribute;
        $attribute->addOption(new Option($data));

        $value = 'some string value';
        $attribute->setTest($value);
        $this->assertEquals($value, $attribute->getTest());
    }


    /**
     * Test that we raise an error when trying to get a nonexistent option.
     *
     * @test
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Overloading error
     */
    public function raiseErrorWhenGettingNonexistentOption()
    {
        $attribute = new Attribute;
        $attribute->getNonexistent();
    }


    /**
     * Test that we raise an error when trying to set a nonexistent option.
     *
     * @test
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Overloading error
     */
    public function raiseErrorWhenSettingNonexistentOption()
    {
        $attribute = new Attribute;
        $attribute->setNonexistent('some value');
    }



}//class ends here