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
namespace ShiftTest\Integration\ShiftContentNew\Type;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\TypeValidator;
use ShiftContentNew\Type\Field\FieldFactory;
use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Type\Field\Attribute\AttributeFactory;

/**
 * Content type aggregate validation test
 * This is an integration test that checks how well content type aggregate
 * validation is functioning meaning we will check all the linked entities and
 * collections.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       integration
 */
class TypeAggregateValidationTest extends TestCase
{

    /**
     * Create aggregate
     * This creates content type aggregate with some fields and attributes.
     * @return \ShiftContentNew\Type\Type
     */
    protected function createAggregate()
    {
        //create type
        $type = new Type(array(
            'name' => 'Content type',
            'description' => 'This content type is used for testing'
        ));

        //add some fields
        $fieldFactory = new FieldFactory($this->sm());

        $field1 = $fieldFactory->createField('file');
        $field1->setName('A file field');
        $field1->setProperty('file');
        $field1->getSettings()->setDestination('data/temp');
        $type->addField($field1);

        $field2 = $fieldFactory->createField('string');
        $field2->setName('A text field');
        $field2->setProperty('text');
        $type->addField($field2);

        //add attributes to fields
        $attributeFactory = new AttributeFactory($this->sm());
        $type->getField('file')->addValidator(
            $attributeFactory->createValidator('NotEmpty')
        );
        $type->getField('text')->addFilter(
            $attributeFactory->createFilter('StringTrim')
        );

        //return aggregate
        return $type;
    }


    /**
     * Test that we are able to validate content type aggregate and pass if
     * it is valid.
     * @test
     */
    public function canValidateTypeAggregateAndPass()
    {
        $type = $this->createAggregate();
        $validator = new TypeValidator($this->sm());
        $result = $validator->validate($type);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we fail on type validation errors.
     * @test
     */
    public function failOnTypeErrors()
    {
        $type = $this->createAggregate();

        //break the type
        $type->setName(null);

        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }


    /**
     * Test that we fail on field validation errors.
     * @test
     */
    public function failOnFieldErrors()
    {
        $type = $this->createAggregate();

        //break the type
        $type->getField('text')->setProperty(null);

        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }


    /**
     * Test that we fail on field settings validation errors.
     * @test
     */
    public function failOnFieldSettingsErrors()
    {
        $type = $this->createAggregate();

        //break the type
        $type->getField('file')->getSettings()->setDestination(null);

        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }

    /**
     * Test that we fail on field configuration errors.
     * @test
     */
    public function failOnFieldMisconfiguration()
    {
        $type = $this->createAggregate();

        //break the type
        $type->getField('file')->setSettings(null);

        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }


    /**
     * Test that we fail on field context errors.
     * @test
     */
    public function failOnFieldContextErrors()
    {
        $type = $this->createAggregate();

        //break the type
        $type->getField('file')->setProperty('property');
        $type->getField('text')->setProperty('property');

        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }


    /**
     * Test that we fail on field attribute configuration errors.
     * @test
     */
    public function failOnAttributeMisconfiguration()
    {
        $type = $this->createAggregate();

        //break the type
        $attributeFactory = new AttributeFactory($this->sm());
        $filter = $attributeFactory->createFilter('Alnum');
        $filter->setClassName(null);
        $type->getField('text')->addFilter($filter);


        //validate
        $validator = new TypeValidator($this->sm());
        $this->assertFalse($validator->validate($type)->isValid());
    }


}//class ends here