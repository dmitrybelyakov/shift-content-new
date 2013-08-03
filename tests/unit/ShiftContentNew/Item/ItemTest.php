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
namespace ShiftTest\Unit\ShiftContentNew\Item;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Item\Item;
use ShiftContentNew\FieldValue\String;

/**
 * Content item tests
 * This holds unit tests for content item entity.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class ItemTest extends TestCase
{

    /**
     * Content item class name
     * @var string
     */
    protected $entityClass = 'ShiftContentNew\Item\Meta';

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
            'publicationDate' => '12-12-2012',
            'title' => 'Test item title',
            'slug' => 'test-item'
        );

    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate content item.
     * @test
     */
    public function canInstantiateContentItem()
    {
        $item = new Item;
        $this->assertInstanceOf($this->entityClass, $item);
    }


    /**
     * Test that we do throw proper exception when adding property
     * without a name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Property must have a name
     */
    public function throwExceptionWhenAddingPropertyWithoutName()
    {
        $item = new Item;
        $item->addProperty(new String);
    }


    /**
     * Test that we do throw proper exception when adding property with an
     * existing name.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Property 'existing' already exists
     */
    public function throwExceptionWhenAddingPropertyWithExistingName()
    {
        $property1 = new String;
        $property1->setName('Existing');

        $property2 = new String;
        $property2->setName('Existing');

        $item = new Item;
        $item->addProperty($property1);
        $item->addProperty($property2);
    }


    /**
     * Test that we are able to add property to an item
     * @test
     */
    public function canAddFieldToAnItem()
    {
        $property = new String;
        $property->setName('Test');

        $item = new Item;
        $item->addProperty($property);

        $properties = $item->getPropertiesCollection()->toArray();
        $this->assertFalse(empty($properties));
    }


    /**
     * Test that we are able to remove property from item
     * @test
     */
    public function canRemoveFieldFromItem()
    {
        $item = new Item;
        $property = new String;
        $property->setName('test');

        //add first
        $item->addProperty($property);
        $properties = $item->getPropertiesCollection()->toArray();
        $this->assertFalse(empty($properties));

        //now remove
        $item->removeProperty($property);
        $properties = $item->getPropertiesCollection()->toArray();
        $this->assertTrue(empty($properties));
    }


    /**
     * Test that we are able to get property by name.
     * @test
     */
    public function canGetPropertyByName()
    {
        $item = new Item;
        $property = new String;
        $property->setName('test');

        //add first
        $item->addProperty($property);
        $properties = $item->getPropertiesCollection()->toArray();
        $this->assertFalse(empty($properties));

        //now get by name
        $this->assertEquals($property, $item->getPropertyByName('test'));
    }


    /**
     * Test that we are able to populate content item and its custom
     * properties from array data set.
     * @test
     */
    public function canPopulateItemFromArray()
    {
        //prepare item
        $property = new String;
        $property->setName('test');
        $item = new Item;
        $item->addProperty($property);

        //prepare data
        $data = array(
            'slug' => 'Test slug',
            'test' => 'Test field data',
            'nonexistent' => 'No such property'
        );


        //now populate
        $item->fromArray($data);
        $this->assertEquals($data['slug'], $item->getSlug());
        $this->assertEquals($data['test'], $item->getTest());
    }


    /**
     * Test that we are able to access an array representation of item
     * @test
     */
    public function canGetItemAsArray()
    {
        $property = new String;
        $property->setName('test');

        $item = new Item;
        $item->addProperty($property);

        //prepare data
        $data = array(
            'slug' => 'Test slug',
            'test' => 'Test field data',
            'nonexistent' => 'No such property'
        );


        $item->fromArray($data);
        $itemArray = $item->toArray();

        $this->assertEquals($data['slug'], $itemArray['slug']);
        $this->assertEquals($data['test'], $itemArray['test']);
    }


    /**
     * Test that we are able to use magic getters for item properties.
     * @test
     */
    public function canUseMagicGetters()
    {
        $property1 = new String();
        $property1->setName('Test');
        $property1->setValue('Test');

        $property2 = new String();
        $property2->setName('Test2');
        $property2->setValue('Test2');

        $property3 = new String();
        $property3->setName('Test3');
        $property3->setValue('Test3');

        $item = new Item;
        $item->addProperty($property1);
        $item->addProperty($property2);
        $item->addProperty($property3);

        $item->setTest('some value');
        $result = $item->getTest2();
        $this->assertEquals($property2->getValue(), $result);
    }


    /**
     * Test that we are able to use magic setters for item properties.
     * @test
     */
    public function canUseMagicSetters()
    {
        $property = new String();
        $property->setName('Test');

        $item = new Item;
        $item->addProperty($property);

        $value = 'Some new value';
        $result = $item->setTest($value);

        $this->assertEquals($item, $result);
        $this->assertEquals($value, $item->getTest());
    }


    /**
     * Test that we trigger an error when nonexistent method is called
     *
     * @test
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Overloading error
     */
    public function triggerErrorWhenNonexistentMethodIsCalled()
    {
        $item = new Item;
        $item->noSuchMethod();
    }


    /**
     * Test that we trigger an error when getter called for
     * nonexistent property.
     *
     * @test
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Overloading error
     */
    public function triggerErrorWhenNonexistentPropertyGetterIsCalled()
    {
        $item = new Item;
        $item->getNoProperty();
    }


    /**
     * Test that we trigger an error when setter called for
     * nonexistent property.
     *
     * @test
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Overloading error
     */
    public function triggerErrorWhenNonexistentPropertySetterIsCalled()
    {
        $item = new Item;
        $item->setNoProperty('somevalue');
    }






}//class ends here