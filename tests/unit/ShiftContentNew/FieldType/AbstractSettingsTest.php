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
namespace ShiftTest\Unit\ShiftContentNew\FieldType;
use Mockery;
use ShiftTest\TestCase;

use ShiftTest\TestAssets\ShiftContentNew\ConcreteFieldSettings as Settings;

/**
 * Abstract settings test
 * This holds unit tests for abstract field settings. Notice that we are testing
 * abstract class through its concrete implementation located under test assets.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AbstractSettingsTest extends TestCase
{
    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();

        $path = realpath(__DIR__ . '/../../../TestAssets/ConcreteSettings.php');
        include_once($path);
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can populate settings from array at instantiation.
     * @test
     */
    public function canPopulateFromArrayAtInstantiation()
    {
        $settings = new Settings(array('concreteProperty' => true));
        $this->assertTrue($settings->getConcreteProperty());
    }


    /**
     * Test that we can get settings as array.
     * @test
     */
    public function canGetArrayRepresentationOfSettings()
    {
        $settings = new Settings;
        $settings = $settings->toArray();

        $this->assertTrue(array_key_exists('id', $settings));
        $this->assertTrue(array_key_exists('concreteProperty', $settings));
    }


    /**
     * Test that we can get settings record id.
     * @test
     */
    public function canGetId()
    {
        $settings = new Settings;
        $this->assertNull($settings->getId());
    }


    /**
     * Test that we can set parent content type field for the settings.
     * @test
     */
    public function canSetField()
    {
        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $settings = new Settings;
        $settings->setField($field);
        $this->assertEquals($field, $settings->getField());
    }


}//class ends here