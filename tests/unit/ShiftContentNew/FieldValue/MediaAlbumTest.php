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

use ShiftContentNew\FieldValue\MediaAlbum;

/**
 * Media album test
 * This holds unit tests for media album field value
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class MediaAlbumTest extends TestCase
{

    /**
     * Test that we are able to instantiate property
     * @test
     */
    public function canInstantiateProperty()
    {
        $property = new MediaAlbum;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\MediaAlbum',
            $property
        );
    }


    /**
     * Test that we are able to set value as MediaAlbum entity
     * @test
     */
    public function canSetValue()
    {
        $value = Mockery::mock('ShiftMedia\Album\Album');
        $value->shouldReceive('getId')->andReturn(987);

        $property = new MediaAlbum;
        $property->setValue($value);
        $this->assertEquals($value, $property->getValue());
    }


    /**
     * Test that we do throw an exception if provided value is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Media item must be of \ShiftMedia\Album\Album
     */
    public function throwExceptionIfValueIsNotMediaItem()
    {
        $value = 'not-a-media-item';
        $property = new MediaAlbum;
        $property->setValue($value);
    }

    /**
     * Test that we do throw an exception if provided media album
     * is not persisted.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Item must be persisted
     */
    public function throwExceptionIfMediaItemIsNotPersisted()
    {
        $value = Mockery::mock('ShiftMedia\Album\Album');
        $value->shouldReceive('getId')->andReturn(null);

        $property = new MediaAlbum;
        $property->setValue($value);
    }


    /**
     * Test that we are able to get an array representation of field value
     * @test
     */
    public function canGetStringPropertyAsArray()
    {
        $mediaAlbumArray = array('me-is-media-album-as-array');

        $value = Mockery::mock('ShiftMedia\Album\Album');
        $value->shouldReceive('getId')->andReturn(123);
        $value->shouldReceive('toArray')->andReturn($mediaAlbumArray);

        $data = array(
            'name' => 'aProperty',
            'value' => $value
        );

        $property = new MediaAlbum($data);
        $propertyArray = $property->toArray();

        $this->assertTrue(is_array($propertyArray));
        $this->assertTrue(array_key_exists('id', $propertyArray));
        $this->assertTrue(array_key_exists('name', $propertyArray));
        $this->assertTrue(array_key_exists('value', $propertyArray));

        $this->assertEquals($data['name'], $propertyArray['name']);
        $this->assertEquals($mediaAlbumArray, $propertyArray['value']);
    }





}//class ends here