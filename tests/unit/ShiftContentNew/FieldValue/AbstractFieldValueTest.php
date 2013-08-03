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
namespace ShiftTest\Unit\ShiftContentNew\FieldValue;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\FieldValue\String;

/**
 * Abstract field value test
 * This holds unit tests for abstract item field value functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AbstractFieldValueTest extends TestCase
{

    /**
     * Test that we are able to instantiate property
     * @test
     */
    public function canInstantiateProperty()
    {
        $property = new String;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\AbstractFieldValue',
            $property
        );
    }


    /**
     * Test that we are able to populate property from array at instantiation.
     * @test
     */
    public function canPopulateFromArrayAtInstantiation()
    {
        $data = array('name' => 'meIsProperty');
        $property = new String($data);
        $this->assertEquals($data['name'], $property->getName());
    }


    /**
     * Test that we are able to get property as array.
     * @test
     */
    public function canGetArrayRepresentationOfProperty()
    {
        $property = new String;
        $property = $property->toArray();
        $this->assertTrue(is_array($property));
        $this->assertTrue(array_key_exists('id', $property));
        $this->assertTrue(array_key_exists('name', $property));
    }


    /**
     * Test that we are able to get property id.
     * @test
     */
    public function canGetId()
    {
        $property = new String;
        $this->assertNull($property->getId());
    }


    /**
     * Test that we are able to set parent content item on property.
     * @test
     */
    public function canSetItem()
    {
        $item = Mockery::mock('ShiftContentNew\Item\Item');
        $property = new String;
        $property->setItem($item);
        $this->assertEquals($item, $property->getItem());
    }


    /**
     * Test that we are able to set property name
     * @test
     */
    public function canSetName()
    {
        $property = new String;
        $property->setName('aProperty');
        $this->assertEquals('aProperty', $property->getName());
    }


    /**
     * Test that property name gets lowercased
     * @test
     */
    public function lowercasePropertyName()
    {
        $name = 'MeIsProperty';

        $property = new String;
        $property->setName($name);
        $this->assertEquals(lcfirst($name), $property->getName());
    }



}//class ends here