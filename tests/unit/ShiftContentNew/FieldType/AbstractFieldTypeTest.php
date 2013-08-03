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

use ShiftTest\TestAssets\ShiftContentNew\FieldType\ConcreteFieldType as Type;
use ShiftTest\TestAssets\ShiftContentNew\FieldType\ErrorFieldType as ErrorType;

/**
 * Abstract field type test
 * This holds unit tests for abstract field type value object.
 * We will test abstract functionality through a concrete implementation
 * located under test assets.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AbstractFieldTypeTest extends TestCase
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
     * Test that we are able to instantiate field type, do initialization
     * and integrity checking.
     * @test
     */
    public function canInstantiateFieldType()
    {
        $type = new Type;

        $this->assertNotNull($type->getName());
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\AbstractFieldType',
            $type
        );
    }


    /**
     * Test that we do throw an exception if one of required properties was
     * not set.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field 'name' is not configured
     */
    public function throwExceptionIfRequiredSettingInMissing()
    {
        new ErrorType;
    }


    /**
     * Test that we do throw an exception if field settings have no
     * corresponding settings validator.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Field 'name' must have settings validator
     */
    public function throwExceptionIfFieldHasSettingsAndNoSettingsValidator()
    {
        $type = new Type;
        $type->setSettingsValidatorClass(null);
        $type->checkIntegrity();
    }


    /**
     * Test that we are able to populate field type from array.
     * @test
     */
    public function canPopulateFromArray()
    {
        $data = $this->data;

        $type = new Type;
        $type->fromArray($data);

        $this->assertEquals($data['name'], $type->getName());
        $this->assertEquals($data['description'], $type->getDescription());
        $this->assertEquals($data['valueClass'], $type->getValueClass());
        $this->assertEquals($data['settingsClass'], $type->getSettingsClass());
        $this->assertEquals($data['editorClass'], $type->getEditorClass());
        $this->assertEquals(
            $data['settingsValidatorClass'],
            $type->getSettingsValidatorClass()
        );
        $this->assertEquals(
            $data['valueProcessorClass'],
            $type->getValueProcessorClass()
        );
    }


    /**
     * Test that we are able to access an array representation of
     * field type.
     *
     * @test
     */
    public function canGetFieldTypeAsArray()
    {
        $type = new Type;
        $type = $type->toArray();

        $this->assertTrue(array_key_exists('name', $type));
        $this->assertTrue(array_key_exists('description', $type));
        $this->assertTrue(array_key_exists('valueClass', $type));
        $this->assertTrue(array_key_exists('settingsClass', $type));
        $this->assertTrue(array_key_exists('settingsValidatorClass', $type));
        $this->assertTrue(array_key_exists('editorClass', $type));
        $this->assertTrue(array_key_exists('valueProcessorClass', $type));
    }

    /**
     * Test that we can set type name.
     * @test
     */
    public function canSetName()
    {
        $name = $this->data['name'];
        $type = new Type;
        $type->setName($name);
        $this->assertEquals($name, $type->getName());

    }


    /**
     * Test that we can set field type description.
     * @test
     */
    public function canSetDescription()
    {
        $description = $this->data['description'];
        $type = new Type;
        $type->setDescription($description);
        $this->assertEquals($description, $type->getDescription());
    }


    /**
     * Test that we can set field type value class.
     * @test
     */
    public function canSetValueClass()
    {
        $valueClass = $this->data['valueClass'];
        $type = new Type;
        $type->setValueClass($valueClass);
        $this->assertEquals($valueClass, $type->getValueClass());
    }


    /**
     * Test that we throw proper exception when trying to set nonexistent
     * value class.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Value class '\BAD' does not exist
     */
    public function throwExceptionWhenSettingNonexistentValueClass()
    {
        $type = new Type;
        $type->setValueClass('BAD');
    }


    /**
     * Test that we can set field type settings class.
     * @test
     */
    public function canSetSettingsClass()
    {
        $settingsClass = $this->data['settingsClass'];
        $type = new Type;
        $type->setSettingsClass($settingsClass);
        $this->assertEquals($settingsClass, $type->getSettingsClass());
    }


    /**
     * Test that we throw proper exception when trying to set nonexistent
     * settings class.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Settings class '\BAD' does not exist
     */
    public function throwExceptionWhenSettingNonexistentSettingsClass()
    {
        $type = new Type;
        $type->setSettingsClass('BAD');
    }


    /**
     * Test that we can set field type settings validator class.
     * @test
     */
    public function canSetSettingsValidatorClass()
    {
        $settingsValidatorClass = $this->data['settingsClass'];
        $type = new Type;
        $type->setSettingsValidatorClass($settingsValidatorClass);
        $this->assertEquals(
            $settingsValidatorClass,
            $type->getSettingsValidatorClass()
        );
    }


    /**
     * Test that we throw proper exception when trying to set nonexistent
     * settings validator class.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Settings validator class '\BAD' does not exist
     */
    public function throwExceptionWhenSettingNonexistentSettingsValidatorClass()
    {
        $type = new Type;
        $type->setSettingsValidatorClass('BAD');
    }


    /**
     * Test that we can set field type editor class.
     * @test
     */
    public function canSetEditorClass()
    {
        $editorClass = $this->data['editorClass'];
        $type = new Type;
        $type->setEditorClass($editorClass);
        $this->assertEquals($editorClass, $type->getEditorClass());
    }


    /**
     * Test that we throw proper exception when trying to set nonexistent
     * editor class.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Editor class '\BAD' does not exist
     */
    public function throwExceptionWhenSettingNonexistentEditorClass()
    {
        $type = new Type;
        $type->setEditorClass('BAD');
    }


    /**
     * Test that we can set field type value processor class.
     * @test
     */
    public function canSetValueProcessorClass()
    {
        $valueProcessorClass = $this->data['valueProcessorClass'];
        $type = new Type;
        $type->setValueProcessorClass($valueProcessorClass);
        $this->assertEquals(
            $valueProcessorClass,
            $type->getValueProcessorClass()
        );
    }


    /**
     * Test that we throw proper exception when trying to set nonexistent
     * value processor class.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Value processor class '\BAD' does not exist
     */
    public function throwExceptionWhenSettingNonexistentValueProcessorClass()
    {
        $type = new Type;
        $type->setValueProcessorClass('BAD');
    }


}//class ends here