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
namespace ShiftTest\Unit\ShiftContentNew\Type;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Type;

/**
 * Content item tests
 * This holds unit tests for content item entity.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class TypeTest extends TestCase
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
            'name' => 'Test Type',
            'description' => 'This type is used for testing',
        );

    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate content type.
     * @test
     */
    public function canInstantiateType()
    {
        $type = new Type;
        $this->assertInstanceOf('ShiftContentNew\Type\Type', $type);
    }


    /**
     * Test that we are able to populate from array upon instantiation.
     * @test
     */
    public function canPopulateFromArrayAtInstantiation()
    {
        $data = $this->data;
        $type = new Type($data);

        $this->assertEquals($data['name'], $type->getName());
        $this->assertEquals($data['description'], $type->getDescription());
    }


    /**
     * Test that we can get an array representation of type.
     * @test
     */
    public function canGetTypeAsArray()
    {
        $type = new Type;
        $type = $type->toArray();

        $this->assertTrue(array_key_exists('id', $type));
        $this->assertTrue(array_key_exists('name', $type));
        $this->assertTrue(array_key_exists('description', $type));
        $this->assertTrue(array_key_exists('fields', $type));
    }


    /**
     * Test that we can get type if vie dedicated accessor.
     * @test
     */
    public function canGetId()
    {
        $type = new Type;
        $this->assertNull($type->getId());
    }


    /**
     * Test that we are able to set content type name
     * @test
     */
    public function canSetName()
    {
        $name = $this->data['name'];
        $type = new Type;
        $type->setName($name);
        $this->assertEquals($name, $type->getName());
    }


    /**
     * Test that we are able to set content type description.
     * @test
     */
    public function canSetDescription()
    {
        $description = $this->data['description'];
        $type = new Type;
        $type->setDescription($description);
        $this->assertEquals($description, $type->getDescription());
    }


    /**
     * Test that we can get an array of type fields.
     * @test
     */
    public function canGetFields()
    {
        $type = new Type;
        $fields = $type->getFields();

        $this->assertTrue(is_array($fields));
        $this->assertTrue(empty($fields));
    }


    /**
     * Test that we are able to get field by property name.
     * @test
     */
    public function canGetFieldByPropertyName()
    {
        $property = 'testField';

        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $field->shouldReceive('getProperty')->andReturn($property);
        $field->shouldReceive('setType');

        $type = new Type;
        $type->addField($field);

        $this->assertNull($type->getField('no-such'));
        $this->assertEquals($field, $type->getField($property));
    }


    /**
     * Test that we are able to add field to content type.
     * @test
     */
    public function canAddFieldToContentType()
    {
        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $field->shouldReceive('setType');

        $type = new Type;
        $type->addField($field);

        $fields = $type->getFields();
        $this->assertContains($field, $fields);
    }


    /**
     * Test that we are able to remove a field from content type.
     * @test
     */
    public function canRemoveFieldFromContentType()
    {
        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $field->shouldReceive('setType');

        $type = new Type;
        $type->addField($field);
        $this->assertContains($field, $type->getFields());

        //now remove
        $type->removeField($field);
        $fields = $type->getFields();
        $this->assertTrue(empty($fields));
    }






}//class ends here