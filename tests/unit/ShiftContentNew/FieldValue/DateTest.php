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

use ShiftContentNew\FieldValue\Date;

/**
 * Date test
 * This holds unit tests for date field value
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class DateTest extends TestCase
{

    /**
     * Test that we are able to instantiate property
     * @test
     */
    public function canInstantiateProperty()
    {
        $property = new Date;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\Date',
            $property
        );
    }


    /**
     * Test that we are able to set value as string and convert it to DateTime
     * @test
     */
    public function canSetValueAsString()
    {
        $value = '30-12-2012 12:00:00';
        $property = new Date;
        $property->setValue($value);

        $this->assertInstanceOf("DateTime", $property->getValue());
        $this->assertEquals(
            'UTC',
            $property->getValue()->getTimezone()->getName()
        );
    }


    /**
     * Test that we do throw an exception if provided date string can not be
     * converted to DateTime object
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage DateTime::__construct(): Failed to parse time
     */
    public function throwExceptionIfDateStringCanNotBeConvertedToDateTime()
    {
        $value = 'not-a-date';
        $property = new Date();
        $property->setValue($value);
    }


    /**
     * Test that we are able to set value as DateTime object.
     * @test
     */
    public function canSetValueAsDateTimeObject()
    {
        $value = new \DateTime('now', new \DateTimeZone('UTC'));
        $property = new Date;
        $property->setValue($value);
        $this->assertEquals($value, $property->getValue());
    }


    /**
     * Test that we convert DateTime value to UTC if it's not.
     * @test
     */
    public function convertDateTimeToUtc()
    {
        $value = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $property = new Date;
        $property->setValue($value);

        $value->setTimezone(new \DateTimezone('UTC'));
        $this->assertEquals($value, $property->getValue());
    }


    /**
     * Test that we are able to get an array representation of field value
     * @test
     */
    public function canGetStringPropertyAsArray()
    {
        $data = array(
            'name' => 'aProperty',
            'value' => '12-12-2012'
        );

        $property = new Date($data);
        $propertyArray = $property->toArray();

        $this->assertTrue(is_array($propertyArray));
        $this->assertTrue(array_key_exists('id', $propertyArray));
        $this->assertTrue(array_key_exists('name', $propertyArray));
        $this->assertTrue(array_key_exists('value', $propertyArray));

        $this->assertEquals($data['name'], $propertyArray['name']);
        $this->assertInstanceOf('DateTime', $propertyArray['value']);
    }





}//class ends here