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
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Type\Field\FieldFactory;
use ShiftContentNew\FieldType\File\FileSettings;

/**
 * This holds integration tests for content types repository.
 * Notice that since content type is an aggregate root for fields, attributes,
 * attribute options we need to extensively test its relational mappings
 * and cascades which is what we do here exactly.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 */
class TypeRepositoryTest extends TestCase
{

    /**
     * Doctrine entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();

        //get db helper
        $this->getDbHelper();

        //set entity manager
        $doctrine = $this->getLocator()->get('Doctrine');
        $this->em = $doctrine->getEntityManager();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we are able to get repository from entity manager by
     * entity name.
     * @test
     */
    public function canGetRepositoryFromEntityManager()
    {
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $this->assertInstanceOf('ShiftContentNew\Type\TypeRepository', $repo);
    }


    /**
     * Test that we are able to inject arbitrary entity manager to be used
     * within repository.
     * @test
     */
    public function canInjectArbitraryEntityManager()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $repo->setEntityManager($em);
        $this->assertEquals($em, $repo->getEntityManager());
    }


    /**
     * Test that we can retrieve content type by unique name.
     * @test
     */
    public function canFindTypeByName()
    {
        $name = 'Type name';

        $type = new Type();
        $type->setName($name);

        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $repo->save($type);

        $this->assertNotNull($type->getId());
        $this->em->clear();
        unset($type);

        //now find by name
        $type = $repo->findByName($name);
        $this->assertEquals($name, $type->getName());
    }


    /**
     * Test that we do throw proper exception on database errors when saving.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DatabaseException
     * @expectedExceptionMessage Database error
     */
    public function throwExceptionOnDatabaseErrorWhenSaving()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist')->andThrow('Exception');

        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $repo->setEntityManager($em);

        $type = Mockery::mock('ShiftContentNew\Type\Type');
        $repo->save($type);
    }


    /**
     * Test that we are able to save type
     * @test
     */
    public function canSaveType()
    {
        $type = new Type();
        $type->setName('Test type');

        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $result = $repo->save($type);

        $this->assertInstanceOf('ShiftContentNew\Type\Type', $result);
        $this->assertNotNull($type->getId());

        $repo->getEntityManager()->clear();
        $type = $repo->findOneById(1);
    }


    /**
     * Test that we throw proper exception on database errors when deleting.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DatabaseException
     * @expectedExceptionMessage Database error
     */
    public function throwExceptionOnDatabaseErrorWhenDeleting()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('remove')->andThrow('Exception');

        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $repo->setEntityManager($em);

        $type = Mockery::mock('ShiftContentNew\Type\Type');
        $repo->delete($type);
    }


    /**
     * Test that we are able to delete an type
     * @test
     */
    public function canDeleteType()
    {
        $type = new Type;
        $type->setName('Test type');

        //save first
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $repo->save($type);

        $savedId = $type->getId();
        $this->assertNotNull($savedId);
        $this->em->clear();
        unset($type);

        //now find and delete
        $type = $repo->findOneById($savedId);
        $this->assertInstanceOf('ShiftContentNew\Type\Type', $type);
        $repo->delete($type);
        $this->assertNull($type->getId());
        $this->assertNull($repo->findOneById($savedId));
    }


    /**
     * Test that we are able to save type along with its fields in one go.
     * @test
     */
    public function canSaveTypeWithFields()
    {
        $type = new Type;
        $type->setName('Test name');

        $factory = new FieldFactory($this->getLocator());
        $field = $factory->createField('file');
        $field->setName('First field');
        $field->setProperty('first');
        $field->getSettings()->setDestination('data/tmp');
        $type->addField($field);

        //save
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($field, $type);
        $this->em->clear();

        //now find saved
        $type = $repo->findOneById($savedId);
        $this->assertEquals($savedId, $type->getId());

        //assert fields not empty (saved by relation)
        $fields = $type->getFields();
        $this->assertFalse(empty($fields));
    }


    /**
     * Test that deleting a type also deletes all its fields by relation.
     * @test
     */
    public function deletingTypeDeletesAllItsFields()
    {
        $type = new Type;
        $type->setName('Test name');

        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //save type with fields
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();
        $this->em->clear();

        //now find it and delete
        $type = $repo->findOneById($savedId);
        $this->assertEquals($savedId, $type->getId());
        $repo->delete($type);

        //assert fields deleted as well
        $fieldsRepo = $this->em->getRepository(
            'ShiftContentNew\Type\Field\Field'
        );

        $fields =  $fieldsRepo->findAll();
        $this->assertTrue(empty($fields));
    }


    /**
     * Test that we are bale to save type and get its fields and
     * attributes (filters and validators) saved by relation.
     * @test
     */
    public function canSaveTypeWithFieldsAndAttributes()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //get factory
        $attrFactory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $attributeFactory = $this->getLocator()->get($attrFactory);

        //add filter to field
        $alnum = $attributeFactory->createFilter('Alnum');
        $alnum->setAllowWhitespace(true);
        $type->getField('first')->addFilter($alnum);

        //add validator to field
        $alpha = $attributeFactory->createValidator('Alpha');
        $alpha->setAllowWhitespace(true);
        $type->getField('first')->addValidator($alpha);

        //save type with fields and attributes
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $alnum, $alpha);
        $this->em->clear();

        //now find it
        $type = $repo->findOneById($savedId);
        $this->assertEquals($savedId, $type->getId());
        $field = array_shift($type->getFields());

        //assert has filters and validators
        $filters = $field->getFilters();
        $validators = $field->getValidators();
        $this->assertFalse(empty($filters));
        $this->assertFalse(empty($validators));
    }


    /**
     * Test that by deleting a type we also delete its fields and attributes.
     * @test
     */
    public function canDeleteTypeWithFieldsAndAttributes()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //get factory
        $attrFactory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $attributeFactory = $this->getLocator()->get($attrFactory);

        //add filter to field
        $alnum = $attributeFactory->createFilter('Alnum');
        $alnum->setAllowWhitespace(true);
        $type->getField('first')->addFilter($alnum);

        //add validator to field
        $alpha = $attributeFactory->createValidator('Alpha');
        $alpha->setAllowWhitespace(true);
        $type->getField('first')->addValidator($alpha);

        //save type with fields and attributes
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $alnum, $alpha);
        $this->em->clear();

        //now find and delete it
        $type = $repo->findOneById($savedId);
        $repo->delete($type);

        //assert fields deleted (already tested above)
        $fieldsRepo = $this->em->getRepository(
            'ShiftContentNew\Type\Field\Field'
        );
        $fields = $fieldsRepo->findAll();
        $this->assertEmpty($fields);


        //assert field attributes deleted as well (by relation)
        $attributesRepo = $this->em->getRepository(
            'ShiftContentNew\Type\Field\Attribute\Attribute'
        );
        $attributes = $attributesRepo->findAll();
        $this->assertEmpty($attributes);
    }


    /**
     * Test that we can persist content type with fields, attributes and
     * their options.
     * @test
     */
    public function canSaveTypeWithFieldsAttributesAndOptions()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //get factory
        $attrFactory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $attributeFactory = $this->getLocator()->get($attrFactory);

        //add filter to field
        $alnum = $attributeFactory->createFilter('Alnum');
        $alnum->setAllowWhitespace(true);
        $type->getField('first')->addFilter($alnum);

        //save type with fields and attributes
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $alnum);
        $this->em->clear();

        //now find it
        $type = $repo->findOneById($savedId);
        $field = array_shift($type->getFields());
        $filter = array_shift($field->getFilters());
        $option = array_shift($filter->getOptions());

        //assert option saved by relation
        $this->assertNotNull($option->getId());
    }


    /**
     * Test that when we delete a type we also delete its fields, attributes
     * and their options by relation.
     * @test
     */
    public function canDeleteTypeWithFieldsAttributesAndOptions()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        $attrFactory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $attributeFactory = $this->getLocator()->get($attrFactory);

        //add filter to field
        $alnum = $attributeFactory->createFilter('Alnum');
        $alnum->setAllowWhitespace(true);
        $type->getField('first')->addFilter($alnum);

        //save type with fields and attributes
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $alnum);
        $this->em->clear();

        //now find and delete it
        $type = $repo->findOneById($savedId);
        $repo->delete($type);

        //assert all options deleted as well (by relation)
        $optionsRepo = $this->em->getRepository(
            'ShiftContentNew\Type\Field\Attribute\AttributeOption'
        );
        $options = $optionsRepo->findAll();
        $this->assertTrue(empty($options));
    }


    /**
     * Test that we can persist type, its fields and settings (by relation)
     * @test
     */
    public function canSaveTypeWithFieldsAndSettings()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //add settings to field (usually done by factory)
        $settings = new FileSettings;
        $field->setSettings($settings);
        $field->getSettings()->setDestination('/temp');


        //save type with fields and settings
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $settings);
        $this->em->clear();

        //assert setting persisted by relation
        $type = $repo->findOneById($savedId);
        $field = array_shift($type->getFields());
        $this->assertNotNull($field->getSettings()->getId());
    }


    /**
     * Test that we are able to delete type along with its fields and
     * their settings by relation.
     * @test
     */
    public function canDeleteTypeWithFieldsAndSettings()
    {
        $type = new Type;
        $type->setName('Test name');

        //create field
        $field = new Field;
        $field->setName('First field');
        $field->setProperty('first');
        $field->setFieldType('file');
        $type->addField($field);

        //add settings to field (usually done by factory)
        $settings = new FileSettings;
        $field->setSettings($settings);
        $field->getSettings()->setDestination('/temp');


        //save type with fields and settings
        $repo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $saved = $repo->save($type);
        $savedId = $saved->getId();

        //clear
        unset($type, $field, $settings);
        $this->em->clear();

        //now find and delete it
        $type = $repo->findOneById($savedId);
        $repo->delete($type);

        //assert all options deleted as well (by relation)
        $settingsRepo = $this->em->getRepository(
            'ShiftContentNew\FieldType\AbstractSettings'
        );
        $settings = $settingsRepo->findAll();
        $this->assertTrue(empty($settings));
    }













}//class ends here