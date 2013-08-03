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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Type\Field\FieldFactory;
use ShiftContentNew\Type\Field\FieldValidator;
use ShiftContentNew\Type\Field\Attribute\Attribute;

/**
 * Content type field validator tests
 * This holds unit tests for content type field entity validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldValidatorTest extends TestCase
{

    /**
     * Test data to populate item
     * @var array
     */
    protected $data = array();

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate content type.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new FieldValidator($this->getLocator());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\FieldValidator',
            $validator
        );
    }

    /**
     * Test that we are able to validate a field and pass if it is correct.
     * @test
     */
    public function canValidateFieldAndPass()
    {
        $factory = new FieldFactory($this->getLocator());
        $field = $factory->createField('file');

        $field->setType(Mockery::mock('ShiftContentNew\Type\Type'));
        $field->setName('A field');
        $field->setProperty('aProperty');
        $field->getSettings()->setDestination('data/temp');

        $validator = new FieldValidator($this->getLocator());
        $result = $validator->validate($field);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we can fail and get all the proper errors.
     * @test
     */
    public function canValidateFieldAndFail()
    {
        $field = new Field;
        $field->addFilter(new Attribute(array('type' => 'filter')));

        $validator = new FieldValidator($this->getLocator());
        $result = $validator->validate($field);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();

        //property
        $this->assertTrue(isset($errors['name']));
        $this->assertTrue(isset($errors['property']));
        $this->assertTrue(isset($errors['fieldType']));

        //entities
        $this->assertTrue(isset($errors['type']));

        //collections
        $this->assertTrue(isset($errors['attributes']));

        //state validator
        $this->assertTrue(isset($errors['stateErrors']['configuration']));
    }


    /**
     * Test that we can validate uniqueness of field name within content type
     * @test
     */
    public function canValidateFieldNameUniqueness()
    {
        $factory = new FieldFactory($this->getLocator());
        $types = $factory->getFieldTypes();

        $field1 = $factory->createFieldOfType($types['file']);
        $field1->setName('A field');
        $field1->setProperty('aPropety');

        $field2 = $factory->createFieldOfType($types['file']);
        $field2->setName('A field');
        $field2->setProperty('anotherPropety');


        $type = new Type;
        $type->addField($field1);
        $type->addField($field2);

        $validator = new FieldValidator($this->getLocator());
        $result = $validator->validate($field1, $type);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['name']['nameNotUnique']));
    }


    /**
     * Test that we can validate uniqueness of field property within
     * content type
     * @test
     */
    public function canValidateFieldPropertyUniqueness()
    {
        $factory = new FieldFactory($this->getLocator());
        $types = $factory->getFieldTypes();

        $field1 = $factory->createFieldOfType($types['file']);
        $field1->setName('A field');
        $field1->setProperty('aPropety');

        $field2 = $factory->createFieldOfType($types['file']);
        $field2->setName('Another field');
        $field2->setProperty('aPropety');


        $type = new Type;
        $type->addField($field1);
        $type->addField($field2);

        $validator = new FieldValidator($this->getLocator());
        $result = $validator->validate($field1, $type);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['property']['propertyNotUnique']));
    }



}//class ends here