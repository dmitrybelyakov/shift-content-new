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
namespace ShiftTest\Integration\ShiftContentNew\Type\Field;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\FieldFactory;


/**
 * Field factory test
 * This holds integration tests for field factory that is responsible
 * for creation of content type fields based on their configured definitions.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       integration
 */
class FieldFactoryTest extends TestCase
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
     * Test that we are able to instantiate field factory
     * @test
     */
    public function canInstantiateFactory()
    {
        $factory = new FieldFactory($this->sm());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\FieldFactory',
            $factory
        );
    }


    /**
     * Test that we are able to inject arbitrary config to be used within
     * factory.
     *
     * @test
     */
    public function canInjectArbitraryConfig()
    {
        $config = array('me-is-config');
        $factory = new FieldFactory($this->sm());
        $factory->setConfig($config);
        $this->assertEquals($config, $factory->getConfig());
    }


    /**
     * Test that we are able to inject arbitrary field type factory.
     * @test
     * @group xxx
     */
    public function canInjectFieldTypeFactory()
    {
        $fieldTypeFactory = Mockery::mock(
            'ShiftContentNew\FieldType\FieldTypeFactory'
        );
        $factory = new FieldFactory($this->sm());
        $factory->setFieldTypeFactory($fieldTypeFactory);

        $this->assertEquals($fieldTypeFactory, $factory->getFieldTypeFactory());
    }


    /**
     * Test that we do obtain config from module bootstrap if none is injected.
     * @test
     */
    public function getConfigFromModuleBootstrapIfNoneInjected()
    {
        $factory = new FieldFactory($this->sm());
        $config = $factory->getConfig();

        $this->assertTrue(is_array($config));
        $this->assertFalse(empty($config));
    }


    /**
     * Test that we are able to get a list of configured field types.
     * @test
     */
    public function canGetFieldTypes()
    {
        $factory = new FieldFactory($this->sm());
        $types = $factory->getFieldTypes();
        $this->assertTrue(is_array($types));
        $this->assertFalse(empty($types));
    }


    /**
     * Test that we are able to get field type object from factory if
     * it is configured.
     * @test
     */
    public function canGetFieldType()
    {
        $factory = new FieldFactory($this->sm());
        $this->assertNull($factory->getFieldType('Not\Exists'));

        $type = $factory->getFieldType(
            'ShiftContentNew\FieldType\File\FileType'
        );

        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\AbstractFieldType',
            $type
        );
    }


    /**
     * Test that we do throw proper exception when creating field that is
     * not configured.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage is not configured
     */
    public function throwExceptionWhenTryingToCreateFieldThatIsNotConfigured()
    {
        $factory = new FieldFactory($this->sm());
        $factory->createFieldOfType('Non\Existent');
    }


    /**
     * Test that we are able to create a field
     * @test
     */
    public function canCreateFieldByClassName()
    {
        $factory = new FieldFactory($this->sm());
        $types = $factory->getFieldTypes();
        $type = $factory->getFieldType($types['file']);

        $field = $factory->createFieldOfType($types['file']);
        $this->assertEquals($types['file'], $field->getFieldType());
        $this->assertInstanceOf(
            $type->getSettingsClass(),
            $field->getSettings()
        );
    }


    /**
     * Test that we are able to assemble a field by its type short name.
     * @test
     */
    public function canCreateFieldByShortName()
    {
        $factory = new FieldFactory($this->sm());
        $field = $factory->createField('file');
        $this->assertInstanceOf('ShiftContentNew\Type\Field\Field', $field);

        //get null for missing short names
        $this->assertNull($factory->createField('non-existent'));
    }


    /**
     * Test that we can create a field and populate it at the same time.
     * @test
     */
    public function canCreateFieldAndPopulateImmediately()
    {
        $data = array(
            'name' => 'A test field',
            'property' => 'testField'
        );

        $factory = new FieldFactory($this->sm());
        $field = $factory->createField('file', $data);
        $this->assertEquals($data['name'], $field->getName());
        $this->assertEquals($data['property'], $field->getProperty());
    }



}//class ends here