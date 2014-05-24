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
namespace ShiftTest\Integration\ShiftContentNew;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\FieldType\FieldTypeFactory;
use ShiftTest\TestAssets\ShiftContentNew\FieldType\ConcreteFieldType as Type;

/**
 * Field type factory test
 * This holds integration tests for field type factory.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 * @group contentUpdate
 */
class FieldTypeFactoryTest extends TestCase
{
    /**
     * Test data set
     * @var array
     */
    protected $data;

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();

        $path = __DIR__.'/../../../TestAssets/FieldType';
        $path = realpath($path);
        include_once($path. '/ConcreteFieldType.php');
        include_once($path. '/ErrorFieldType.php');

        $namespace = '\ShiftContentNew\FieldType\File';
        $this->data = array(
            'name' => 'From array',
            'description' => 'From array',
            'valueClass' => '\ShiftContentNew\FieldValue\String',
            'settingsClass' => $namespace . '\FileSettings',
            'settingsValidatorClass' => $namespace . '\FileSettingsValidator',
            'editorClass' => '\Zend\Form\Element\File',
            'valueProcessorClass' => $namespace . '\FileValueProcessor',
        );
    }

    // ------------------------------------------------------------------------



    /**
     * Test that we are able to instantiate value class
     * @test
     */
    public function canInstantiateValue()
    {
        $factory = new FieldTypeFactory($this->sm());
        $type = new Type;

        $class = $this->data['valueClass'];
        $this->assertInstanceOf($class, $factory->getValue($type));
    }



    /**
     * Test that we throw proper exception if value class is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field value must be of
     */
    public function throwExceptionIfValueClassIsOfBadType()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());

        $type->setValueClass('Exception'); //just to test
        $factory->getValue($type);
    }


    /**
     * Test that we are able to instantiate settings class
     * @test
     */
    public function canInstantiateSettings()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $class = $this->data['settingsClass'];
        $this->assertInstanceOf($class, $factory->getSettings($type));
    }



    /**
     * Test that we throw proper exception if settings class is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field settings must be of
     */
    public function throwExceptionIfSettingsClassIsOfBadType()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $type->setSettingsClass('Exception'); //just to test
        $factory->getSettings($type);
    }


    /**
     * Test that we are able to instantiate settings validator class
     * @test
     */
    public function canInstantiateSettingsValidator()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $class = $this->data['settingsValidatorClass'];
        $this->assertInstanceOf($class, $factory->getSettingsValidator($type));
    }


    /**
     * Test that we throw proper exception if settings class is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field settings validator must be of
     */
    public function throwExceptionIfSettingsValidatorClassIsOfBadType()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $type->setSettingsValidatorClass('Exception'); //just to test
        $factory->getSettingsValidator($type);
    }


    /**
     * Test that we are able to instantiate editor class
     * @test
     */
    public function canInstantiateEditor()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $class = $this->data['editorClass'];
        $this->assertInstanceOf($class, $factory->getEditor($type));
    }



    /**
     * Test that we throw proper exception if editor class is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field editor must be of
     */
    public function throwExceptionIfEditorClassIsOfBadType()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $type->setEditorClass('Exception'); //just to test
        $factory->getEditor($type);
    }


    /**
     * Test that we are able to instantiate value processor class
     * @test
     */
    public function canInstantiateValueProcessor()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $class = $this->data['valueProcessorClass'];
        $this->assertInstanceOf($class, $factory->getValueProcessor($type));
    }



    /**
     * Test that we throw proper exception if value processor class
     * is of bad type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field value processor must be of
     */
    public function throwExceptionIfValueProcessorClassIsOfBadType()
    {
        $type = new Type;
        $factory = new FieldTypeFactory($this->sm());
        $type->setValueProcessorClass('Exception'); //just to test
        $factory->getValueProcessor($type);
    }






}//class ends here