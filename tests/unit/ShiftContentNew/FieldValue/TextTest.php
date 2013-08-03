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

use ShiftContentNew\FieldValue\Text;

/**
 * Text test
 * This holds unit tests text field value
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class TextTest extends TestCase
{

    /**
     * Test that we are able to instantiate property
     * @test
     */
    public function canInstantiateProperty()
    {
        $property = new Text;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\Text',
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
        $property = new Text;
        $property->setValue($value);
        $this->assertEquals("$value", $property->getValue());
        $this->assertTrue(is_string($property->getValue()));
    }


    /**
     * Test that we are able to get an array representation of field value
     * @test
     */
    public function canGetPropertyAsArray()
    {
        $data = array(
            'name' => 'aProperty',
            'value' => 'some string value'
        );

        $property = new Text($data);
        $propertyArray = $property->toArray();

        $this->assertTrue(is_array($propertyArray));
        $this->assertTrue(array_key_exists('id', $propertyArray));
        $this->assertTrue(array_key_exists('name', $propertyArray));
        $this->assertTrue(array_key_exists('value', $propertyArray));

        $this->assertEquals($data['name'], $propertyArray['name']);
        $this->assertEquals($data['value'], $propertyArray['value']);
    }





}//class ends here