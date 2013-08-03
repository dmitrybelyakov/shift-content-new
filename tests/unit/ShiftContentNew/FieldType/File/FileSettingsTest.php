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
namespace ShiftTest\Unit\ShiftContentNew\FieldType\File;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\FieldType\File\FileSettings;

/**
 * File settings test
 * This holds unit tests for file settings entity.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class FileSettingsTest extends TestCase
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
     * Test that we can instantiate settings.
     * @test
     */
    public function canInstantiateSettings()
    {

        $settings = new FileSettings;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\File\FileSettings',
            $settings
        );
    }


    /**
     * Test that we can get an array representation aof settings.
     * @test
     */
    public function canGetSettingsAsArray()
    {
        $settings = new FileSettings;
        $settings = $settings->toArray();

        $this->assertTrue(array_key_exists('id', $settings));
        $this->assertTrue(array_key_exists('destination', $settings));
        $this->assertTrue(array_key_exists('mode', $settings));
    }


    /**
     * Test that we are able to set destination setting.
     * @test
     */
    public function canSetDestination()
    {
        $destination = '/tmp';
        $settings = new FileSettings;
        $settings->setDestination($destination);
        $this->assertEquals($destination, $settings->getDestination());
    }


    /**
     * Test that we are able to set mode setting
     * @test
     */
    public function canSetMode()
    {
        $mode = 'createFolder';
        $settings = new FileSettings;
        $settings->setMode($mode);
        $this->assertEquals($mode, $settings->getMode());
    }


    /**
     * Test that we can check mode setting with corresponding accessor methods.
     * @test
     */
    public function canCheckModeWithAccessors()
    {
        $settings = new FileSettings;

        //default
        $this->assertTrue($settings->uploadToDestination());
        $this->assertFalse($settings->createFolder());

        $settings->setMode('createFolder');
        $this->assertTrue($settings->createFolder());
        $this->assertFalse($settings->uploadToDestination());
    }


}//class ends here