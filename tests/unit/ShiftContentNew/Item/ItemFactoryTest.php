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

use ShiftContentNew\Item\ItemFactory;


/**
 * Item factory test
 * This holds unit tests for item factory functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class ItemFactoryTest extends TestCase
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
     * Test that we are able to instantiate the factory
     * @test
     */
    public function canInstantiateFactory()
    {
        $factory = new ItemFactory($this->getLocator());
        $this->assertInstanceOf('ShiftContentNew\Item\ItemFactory', $factory);
    }


    /**
     * Test that we are able to inject arbitrary content types service.
     * @test
     */
    public function canInjectTypeService()
    {
        $typeService = Mockery::mock('ShiftContentNew\Type\TypeService');
        $factory = new ItemFactory($this->getLocator());
        $factory->setTypeService($typeService);
        $this->assertEquals($typeService, $factory->getTypeService());
    }


    /**
     * Test that we do obtain type service from locator if none injected.
     * @test
     */
    public function getTypeServiceFromLocatorIfNoneInjected()
    {
        $factory = new ItemFactory($this->getLocator());
        $typeService = $factory->getTypeService();
        $this->assertInstanceOf(
            'ShiftContentNew\Type\TypeService',
            $typeService
        );
    }


    /**
     * Test that we are able to inject arbitrary field type factory.
     * @test
     */
    public function canInjectFieldTypeFactory()
    {
        $fieldTypeFactory = 'ShiftContentNew\FieldType\FieldTypeFactory';
        $fieldTypeFactory = Mockery::mock($fieldTypeFactory);
        $factory = new ItemFactory($this->getLocator());
        $factory->setFieldTypeFactory($fieldTypeFactory);
        $this->assertEquals($fieldTypeFactory, $factory->getFieldTypeFactory());
    }


    /**
     * Test that we do obtain field type factory from locator if none injected.
     * @test
     */
    public function getFieldTypeFactoryFromLocatorIfNoneInjected()
    {
        $factory = new ItemFactory($this->getLocator());
        $fieldTypeFactory = $factory->getFieldTypeFactory();
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\FieldTypeFactory',
            $fieldTypeFactory
        );
    }







}//class ends here