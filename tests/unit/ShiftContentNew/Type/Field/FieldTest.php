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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Field;

/**
 * Type field test
 * This holds unit tests for content type field functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldTest extends TestCase
{

    /**
     * Test data to populate item
     * @var array
     */
    protected $data = array();

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();

        //prepare data for testing
        $this->data = array(
            'name' => 'Example field',
            'property' => 'example',
            'fieldType' => 'file'
        );

    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate content type field.
     * @test
     */
    public function canInstantiateField()
    {
        $field = new Field;
        $this->assertInstanceOf('ShiftContentNew\Type\Field\Field', $field);
    }


    /**
     * Test that we can populate field from array upon instantiation.
     * @test
     */
    public function canPopulateFromArrayAtCreation()
    {
        $data = $this->data;
        $field = new Field($data);

        $this->assertEquals($data['name'], $field->getName());
        $this->assertEquals($data['property'], $field->getProperty());
        $this->assertEquals($data['fieldType'], $field->getFieldType());
    }


    /**
     * Test that we are able to access an array representation of field.
     * @test
     */
    public function canGetFieldAsArray()
    {
        $field = new Field;
        $field = $field->toArray();

        $this->assertTrue(array_key_exists('name', $field));
        $this->assertTrue(array_key_exists('property', $field));
        $this->assertTrue(array_key_exists('fieldType', $field));
        $this->assertTrue(array_key_exists('settings', $field));
        $this->assertTrue(array_key_exists('filters', $field));
        $this->assertTrue(array_key_exists('validators', $field));
    }


    /**
     * Test that we are able to get field id.
     * @test
     */
    public function canGetId()
    {
        $field = new Field;
        $this->assertNull($field->getId());
    }

    /**
     * Test that we are able to set parent type for the field.
     * @test
     */
    public function canSetType()
    {
        $type = Mockery::mock('ShiftContentNew\Type\Type');
        $field = new Field;
        $field->setType($type);
        $this->assertEquals($type, $field->getType());
    }


    /**
     * Test that we are able to set field name.
     * @test
     */
    public function canSetName()
    {
        $name = $this->data['name'];
        $field = new Field;
        $field->setName($name);
        $this->assertEquals($name, $field->getName());
    }


    /**
     * Test that we are able to set field property name.
     * @test
     */
    public function canSetProperty()
    {
        $property = $this->data['property'];
        $field = new Field;
        $field->setProperty($property);
        $this->assertEquals($property, $field->getProperty());
    }


    /**
     * Test that we are able to set field type.
     * @test
     */
    public function canSetFieldType()
    {
        $type = $this->data['fieldType'];
        $field = new Field;
        $field->setFieldType($type);
        $this->assertEquals($type, $field->getFieldType());
    }


    /**
     * Test that we are able to set field settings.
     * @test
     */
    public function canSetSettings()
    {
        $settings = Mockery::mock('ShiftContentNew\FieldType\AbstractSettings');
        $settings->shouldReceive('setField');

        $field = new Field;
        $field->setSettings($settings);
        $this->assertEquals($settings, $field->getSettings());
    }


    /**
     * Test that we are able to get field attributes.
     * @test
     */
    public function canGetFieldAttributes()
    {
        $field = new Field;
        $attributes = $field->getAttributes();
        $this->assertTrue(is_array($attributes));
        $this->assertTrue(empty($attributes));
    }


    /**
     * Test that we are able to get field filters.
     * @test
     */
    public function canGetFieldFilters()
    {
        $field = new Field;
        $filters = $field->getFilters();
        $this->assertTrue(is_array($filters));
        $this->assertTrue(empty($filters));
    }


    /**
     * Test that we are able to add filter to field.
     * @test
     */
    public function canAddFilter()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $filter = Mockery::mock($attribute);
        $filter->shouldReceive('getType')->andReturn('filter');
        $filter->shouldReceive('setField');

        $field = new Field;
        $field->addFilter($filter);
        $this->assertContains($filter, $field->getFilters());
    }


    /**
     * Test that we throw proper exception when adding filter of bad type
     * to field filters collection.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage This is not a filter
     */
    public function throwExceptionWhenAddingFilterOfBadType()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $filter = Mockery::mock($attribute);
        $filter->shouldReceive('getType')->andReturn('validator');

        $field = new Field;
        $field->addFilter($filter);
    }


    /**
     * Test that we are able to remove filter from content field.
     * @test
     */
    public function canRemoveFilter()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $filter = Mockery::mock($attribute);
        $filter->shouldReceive('getType')->andReturn('filter');
        $filter->shouldReceive('setField');

        //add first
        $field = new Field;
        $field->addFilter($filter);
        $this->assertContains($filter, $field->getFilters());

        //now remove
        $field->removeFilter($filter);
        $filters = $field->getFilters();
        $this->assertTrue(empty($filters));
    }


    /**
     * Test that we are able to add validator to field.
     * @test
     */
    public function canAddValidator()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $validator = Mockery::mock($attribute);
        $validator->shouldReceive('getType')->andReturn('validator');
        $validator->shouldReceive('setField');

        $field = new Field;
        $field->addValidator($validator);
        $this->assertContains($validator, $field->getValidators());
    }


    /**
     * Test that we throw proper exception when adding validator of bad type
     * to field validators collection.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage This is not a validator
     */
    public function throwExceptionWhenAddingValidatorOfBadType()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $validator = Mockery::mock($attribute);
        $validator->shouldReceive('getType')->andReturn('filter');

        $field = new Field;
        $field->addValidator($validator);
    }


    /**
     * Test that we are able to remove validator from content field.
     * @test
     */
    public function canRemoveValidator()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $validator = Mockery::mock($attribute);
        $validator->shouldReceive('getType')->andReturn('validator');
        $validator->shouldReceive('setField');

        //add first
        $field = new Field;
        $field->addValidator($validator);
        $this->assertContains($validator, $field->getValidators());

        //now remove
        $field->removeValidator($validator);
        $validators = $field->getValidators();
        $this->assertTrue(empty($validators));
    }





}//class ends here