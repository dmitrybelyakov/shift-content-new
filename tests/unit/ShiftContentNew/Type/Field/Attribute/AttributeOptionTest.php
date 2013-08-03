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

use ShiftContentNew\Type\Field\Attribute\AttributeOption as Option;

/**
 * Attribute option test
 * This holds unit tests for content type field attribute options.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeOptionTest extends TestCase
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
     * Test that we can instantiate attribute option.
     * @test
     */
    public function canInstantiateOption()
    {
        $option = new Option;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\AttributeOption',
            $option
        );
    }


    /**
     * Test that we can populate an option from array at instantiation time.
     * @test
     */
    public function canPopulateFromArrayAtInstantiation()
    {
        $data = array(
            'type' => 'string',
            'name' => 'Test option',
            'variable' => 'test',
        );

        $option = new Option($data);
        $this->assertEquals($data['type'], $option->getType());
        $this->assertEquals($data['name'], $option->getName());
        $this->assertEquals($data['variable'], $option->getVariable());
    }


    /**
     * Test that we can get option as array.
     * @test
     */
    public function canGetArrayRepresentationOfOption()
    {
        $data = array(
            'type' => 'string',
            'name' => 'Test option',
            'variable' => 'test',
        );

        $option = new Option($data);
        $option = $option->toArray();

        $this->assertEquals($data['type'], $option['type']);
        $this->assertEquals($data['name'], $option['name']);
        $this->assertEquals($data['variable'], $option['variable']);
    }


    /**
     * Test that we can get option id
     * @test
     */
    public function canGetId()
    {
        $option = new Option;
        $this->assertNull($option->getId());
    }

    /**
     * Test that we are able to set parent attribute for the option
     * @test
     */
    public function canSetAttribute()
    {
        $attribute = Mockery::mock(
            'ShiftContentNew\Type\Field\Attribute\Attribute'
        );

        $option = new Option;
        $option->setAttribute($attribute);
        $this->assertEquals($attribute, $option->getAttribute());
    }


    /**
     * Test that we are able to set option name
     * @test
     */
    public function canSetOptionName()
    {
        $name = 'Some option';
        $option = new Option;
        $option->setName($name);
        $this->assertEquals($name, $option->getName());
    }


    /**
     * Test that we are able to set attribute option variable name.
     * @test
     */
    public function canSetOptionVariableName()
    {
        $variable = 'SomeOption';
        $option = new Option;
        $option->setVariable($variable);

        //assert lcfirst and spaces stripped
        $this->assertEquals('someOption', $option->getVariable());
    }


    /**
     * Test that we throw proper exception when setting bad variable name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Invalid variable name
     */
    public function throwExceptionIfInvalidVariableNameIsSet()
    {
        $badVariable = 'bad variable';
        $option = new Option;
        $option->setVariable($badVariable);
    }


    /**
     * Test that we are able to set attribute option variable value type.
     * @test
     */
    public function canSetValueType()
    {
        $type = 'bool';
        $option = new Option;
        $option->setType($type);
        $this->assertEquals($type, $option->getType());
    }


    /**
     * Test that we throw proper exception when setting invalid value type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Invalid value type
     */
    public function throwExceptionWheSettingInvalidValueType()
    {
        $type = 'bad-type';
        $option = new Option;
        $option->setType($type);
    }


    /**
     * Test that we can set an integer option value.
     * @test
     */
    public function canSetIntegerValue()
    {
        $option = new Option;
        $option->setType('int');
        $option->setValue('123');

        $value = $option->getValue();
        $this->assertTrue(is_int($value));
    }


    /**
     * Test that we can set a string option value.
     * @test
     */
    public function canSetStringValue()
    {
        $option = new Option;
        $option->setType('string');
        $option->setValue('some string');

        $value = $option->getValue();
        $this->assertTrue(is_string($value));
    }


    /**
     * Test that we can set a boolean option value.
     * @test
     */
    public function canSetBooleanValue()
    {
        $option = new Option;
        $option->setType('bool');
        $option->setValue('1');

        $value = $option->getValue();
        $this->assertTrue(is_bool($value));
    }


    /**
     * Test that we throw proper exception when invalid value type discovered.
     * Like when type is not set.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Invalid value type
     */
    public function throwExceptionWhenInvalidValueTypeDiscovered()
    {
        $option = new Option;
        $option->setValue('1');
    }




}//class ends here