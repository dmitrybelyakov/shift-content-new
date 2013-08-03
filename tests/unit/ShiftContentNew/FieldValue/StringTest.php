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

use ShiftContentNew\FieldValue\String;

/**
 * String test
 * This holds unit tests string field value
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class StringTest extends TestCase
{

    /**
     * Test that we are able to instantiate property
     * @test
     */
    public function canInstantiateProperty()
    {
        $property = new String;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\String',
            $property
        );
    }


    /**
     * Test that we are able to set value
     * @test
     */
    public function canSetValue()
    {
        $value = 12345;
        $property = new String;
        $property->setValue($value);
        $this->assertEquals("$value", $property->getValue());
        $this->assertTrue(is_string($property->getValue()));
    }


    /**
     * Test that we are able to get string value as array
     * @test
     */
    public function canGetPropertyAsArray()
    {
        $data = array(
            'name' => 'aProperty',
            'value' => 'some string value'
        );

        $property = new String($data);
        $propertyArray = $property->toArray();

        $this->assertTrue(is_array($propertyArray));
        $this->assertTrue(array_key_exists('id', $propertyArray));
        $this->assertTrue(array_key_exists('name', $propertyArray));
        $this->assertTrue(array_key_exists('value', $propertyArray));

        $this->assertEquals($data['name'], $propertyArray['name']);
        $this->assertEquals($data['value'], $propertyArray['value']);
    }





}//class ends here