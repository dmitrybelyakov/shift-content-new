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

use ShiftContentNew\FieldType\FieldTypeFactory;

/**
 * Field type factory test
 * This holds unit tests for field type factory
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldTypeFactoryTest extends TestCase
{

    /**
     * Test that we are able to create factory
     * @test
     */
    public function canInstantiateFactory()
    {
        $factory = new FieldTypeFactory($this->sm());
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\FieldTypeFactory',
            $factory
        );
    }

    /**
     * Test that we are able to inject arbitrary config.
     * @test
     */
    public function canInjectConfig()
    {
        $config = array('me-is-config');
        $factory = new FieldTypeFactory($this->sm());
        $factory->setConfig($config);
        $this->assertEquals($config, $factory->getConfig());
    }

    /**
     * Test that we are able to obtain configuration from module bootstrap
     * if none injected.
     * @test
     */
    public function obtainConfigFromModuleIfNoneInjected()
    {
        $factory = new FieldTypeFactory($this->sm());
        $config = $factory->getConfig();
        $this->assertTrue(is_array($config));
        $this->assertFalse(empty($config));
    }


    /**
     * Test that we are able to get a list of registered fields.
     * @test
     */
    public function canGetRegisteredFields()
    {
        $factory = new FieldTypeFactory($this->sm());
        $types = $factory->getFieldTypes();

        $this->assertTrue(is_array($types));
        $this->assertFalse(empty($types));
        foreach($types as $type)
        {
            $this->assertInstanceOf(
                'ShiftContentNew\FieldType\AbstractFieldType',
                $type
            );
        }



    }

}//class ends here