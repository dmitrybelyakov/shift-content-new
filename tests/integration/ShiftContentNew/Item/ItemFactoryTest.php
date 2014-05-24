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
namespace ShiftTest\Integration\ShiftContentNew\Item;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Item\Item;
use ShiftContentNew\Item\ItemFactory;
use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\Field\FieldFactory;
use ShiftContentNew\Type\Field\Attribute\AttributeFactory;

/**
 * This holds integration tests mapping item factory functionality of
 * assembling items and item validators from given content types.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
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


    /**
     * Create type with field and attributes
     * Assembles content type with fields, filters and attributes.
     * @return \ShiftContentNew\Type\Type
     */
    protected function createTypeWithFieldsAndAttributes()
    {
        $type = new Type(array(
            'name' => 'Test content type',
            'description' => 'Tis type is used for testing',
        ));

        $fieldFactory = new FieldFactory($this->sm());
        $attributeFactory = new AttributeFactory;

        //field 1
        $field1 = $fieldFactory->createField('string', array(
            'name' => 'String field one',
            'property' => 'stringOne'
        ));

        $field1->addFilter($attributeFactory->createFilter(
            'StringTrim',
            array('charList' => 'a,b,c')
        ));
        $field1->addFilter($attributeFactory->createFilter(
            'Alnum',
            array('allowWhitespace' => true)
        ));
        $field1->addValidator($attributeFactory->createValidator('Digits'));
        $field1->addValidator($attributeFactory->createValidator(
            'Alnum',
            array('allowWhiteSpace' => false)
        ));

        //field 2
        $field2 = $fieldFactory->createField('string', array(
            'name' => 'String field two',
            'property' => 'stringTwo'
        ));

        $field2->addFilter($attributeFactory->createFilter(
            'StringTrim',
            array('charList' => 'd,e,f'))
        );
        $field2->addFilter($attributeFactory->createFilter(
            'Alnum',
            array('allowWhitespace' => true)
        ));
        $field2->addValidator($attributeFactory->createValidator('Digits'));
        $field2->addValidator($attributeFactory->createValidator(
            'StringLength',
            array('min' => 3, 'max' => 100)
        ));

        //add fields to content type
        $type->addField($field1);
        $type->addField($field2);

        return $type;
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we are able to retrieve factory from locator
     * @test
     */
    public function canGetServiceFromLocator()
    {
        $factoryName = 'ShiftContentNew\Item\ItemFactory';
        $factory = $this->sm()->get($factoryName);
        $this->assertInstanceOf($factoryName, $factory);
    }



    /**
     * Test that we do throw an exception when creating content item from
     * content type id that does not exist.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Unable to locate content type
     */
    public function throwExceptionIfCreatingItemOfTypeIdThatDoesNotExist()
    {
        $typeService = Mockery::mock('ShiftContentNew\Type\TypeService');
        $typeService->shouldReceive('getType')->andReturn(null);

        $factory = new ItemFactory($this->sm());
        $factory->setTypeService($typeService);
        $factory->createItemOfType(1);
    }


    /**
     * Test that we are able to create item from content type blue print.
     * @test
     */
    public function canCreateItemOfType()
    {
        //create a type first
        $type = new Type(array(
            'name' => 'Test content type',
            'description' => 'This is used for testing'
        ));

        //then some fields
        $factory = new FieldFactory($this->sm());

        $field1 = $factory->createField('file', array(
            'name' => 'File field',
            'property' => 'aFile'
        ));
        $field2 = $factory->createField('string', array(
            'name' => 'String field',
            'property' => 'aString'
        ));
        $field3 = $factory->createField('string', array(
            'name' => 'Another string field',
            'property' => 'anotherString'
        ));

        //add fields to type
        $type->addField($field1)
            ->addField($field2)
            ->addField($field3);

        //now create item of this type
        $factory = new ItemFactory($this->sm());
        $item = $factory->createItemOfType($type);

        //assert properties added
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\AbstractFieldValue',
            $item->getPropertyByName('aFile')
        );
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\AbstractFieldValue',
            $item->getPropertyByName('aString')
        );
        $this->assertInstanceOf(
            'ShiftContentNew\FieldValue\AbstractFieldValue',
            $item->getPropertyByName('anotherString')
        );
    }


    /**
     * Test that we do throw an exception when creating content item validator
     * from content type id that does not exist.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Unable to locate content type
     */
    public function throwExceptionIfCreatingValidatorOfTypeIdThatDoesNotExist()
    {
        $typeService = Mockery::mock('ShiftContentNew\Type\TypeService');
        $typeService->shouldReceive('getType')->andReturn(null);

        $factory = new ItemFactory($this->sm());
        $factory->setTypeService($typeService);
        $factory->createValidatorOfType(1);
    }


    /**
     * Test that we do throw an exception when creating content item validator
     * for an item without content type set.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage This content item has no type
     */
    public function throwExceptionIfCreatingValidatorForAnItemWithoutType()
    {
        $factory = new ItemFactory($this->sm());
        $factory->createValidatorForItem(new Item);
    }


    /**
     * Test that we are able to create validator for content item with type.
     * @test
     */
    public function canCreateValidatorForItem()
    {
        $item = new Item;
        $item->setContentType(new Type);

        $factory = new ItemFactory($this->sm());
        $validator = $factory->createValidatorForItem($item);
        $this->assertInstanceOf(
            'ShiftContentNew\Item\ItemValidator',
            $validator
        );
    }

    /**
     * Test that we can use item validator as is to validate meta properties
     * and fail.
     * @test
     */
    public function canDoBasicMetaValidationAndFail()
    {
        $item = new Item;
        $item->setContentType(new Type);

        $factory = new ItemFactory($this->sm());
        $validator = $factory->createValidatorForItem($item);

        $item->setContentType(null);

        $result = $validator->validate($item);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(array_key_exists('author', $errors));
        $this->assertTrue(array_key_exists('contentType', $errors));
        $this->assertTrue(array_key_exists('publicationDate', $errors));
        $this->assertTrue(array_key_exists('title', $errors));
        $this->assertTrue(array_key_exists('slug', $errors));
    }


    /**
     * Test that we can use item validator as is to validate meta properties
     * and pass validation if meta is correct.
     * @test
     */
    public function canDoBasicMetaValidationAndPass()
    {
        $item = new Item(array(
            'contentType' => new Type,
            'publicationDate' => new \DateTime('now', new \DateTimeZone('UTC')),
            'title' => 'A test item',
            'slug' => 'me-is-url',
            'author' => Mockery::mock('ShiftUser\User\BaseUser'),
        ));

        $factory = new ItemFactory($this->sm());
        $validator = $factory->createValidatorForItem($item);
        $this->assertTrue($validator->validate($item)->isValid());
    }


    /**
     * Test that we are able to create validator for content type with custom
     * fields an attributes.
     * @test
     */
    public function canCreateValidatorFromTypeWithFieldsAndAttributes()
    {
        $type = $this->createTypeWithFieldsAndAttributes();

        //now create validator from type
        $itemFactory = new ItemFactory($this->sm());
        $validator = $itemFactory->createValidatorOfType($type);
        $this->assertInstanceOf(
            'ShiftCommon\Model\Entity\EntityValidator',
            $validator
        );

        $properties = $validator->getProperties();
        $field1 = $properties['stringOne'];
        $field2 = $properties['stringTwo'];

        //check filed1 filters
        $field1Filters = $field1->getFilters();
        $this->assertTrue(isset($field1Filters['Zend\Filter\StringTrim']));
        $this->assertEquals(
            'a,b,c',
            $field1Filters['Zend\Filter\StringTrim']->getCharList()
        );

        $this->assertTrue(isset($field1Filters['Zend\Filter\Alnum']));
        $this->assertTrue(
            $field1Filters['Zend\Filter\Alnum']->getAllowWhitespace()
        );


        //check filed1 validators
        $field1Validators = $field1->getValidators();
        $this->assertTrue(isset($field1Validators['Zend\Validator\Digits']));
        $this->assertTrue(isset($field1Validators['Zend\Validator\Alnum']));
        $this->assertFalse(
            $field1Validators['Zend\Validator\Alnum']->getAllowWhitespace()
        );


        //check filed2 filters
        $field2Filters = $field2->getFilters();
        $this->assertTrue(isset($field2Filters['Zend\Filter\StringTrim']));
        $this->assertEquals(
            'd,e,f',
            $field2Filters['Zend\Filter\StringTrim']->getCharList()
        );
        $this->assertTrue(isset($field2Filters['Zend\Filter\Alnum']));
        $this->assertTrue(
            $field2Filters['Zend\Filter\Alnum']->getAllowWhitespace()
        );


        //check filed2 validators
        $field2Validators = $field2->getValidators();
        $this->assertTrue(isset($field2Validators['Zend\Validator\Digits']));
        $this->assertTrue(
            isset($field2Validators['Zend\Validator\StringLength'])
        );
        $this->assertEquals(
            3,
            $field2Validators['Zend\Validator\StringLength']->getMin()
        );
        $this->assertEquals(
            100,
            $field2Validators['Zend\Validator\StringLength']->getMax()
        );
    }


    /**
     * Test that we are able to validate custom item properties and fail.
     * @test
     */
    public function canValidateCustomItemPropertiesAndFail()
    {
        $type = $this->createTypeWithFieldsAndAttributes();
        $itemFactory = new ItemFactory($this->sm());

        $item = $itemFactory->createItemOfType($type);
        $validator = $itemFactory->createValidatorForItem($item);

        $result = $validator->validate($item);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['author']));
        $this->assertTrue(isset($errors['publicationDate']));
        $this->assertTrue(isset($errors['title']));
        $this->assertTrue(isset($errors['slug']));

        //and custom ones
        $this->assertTrue(isset($errors['stringOne']));
        $this->assertTrue(isset($errors['stringTwo']));
    }


    /**
     * Test that we are able to validate custom item properties and pass.
     * @test
     */
    public function canValidateCustomItemPropertiesAndPass()
    {
        $type = $this->createTypeWithFieldsAndAttributes();
        $itemFactory = new ItemFactory($this->getLocator());

        $item = $itemFactory->createItemOfType($type, array(
            'publicationDate' => new \DateTime('now', new \DateTimeZone('UTC')),
            'title' => 'A test item',
            'slug' => 'me-is-url',
            'author' => Mockery::mock('ShiftUser\User\BaseUser'),
            'stringOne' => '1234567890',
            'stringTwo' => '123456.7890'
        ));


        $validator = $itemFactory->createValidatorForItem($item);
        $result = $validator->validate($item);
        $this->assertTrue($result->isValid());
    }





}//class ends here