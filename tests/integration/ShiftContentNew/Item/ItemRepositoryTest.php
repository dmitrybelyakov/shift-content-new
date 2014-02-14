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
use ShiftContentNew\Type\Type;

/**
 * This holds integration tests for content items repository
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 */
class ItemRepositoryTest extends TestCase
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
        $doctrine = $this->getLocator()->get('ShiftDoctrine\Container');
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
        $entityName = 'ShiftContentNew\Item\Item';
        $repo = $this->em->getRepository($entityName);
        $this->assertInstanceOf('ShiftContentNew\Item\ItemRepository', $repo);
    }


    /**
     * Test that we are able to inject arbitrary entity manager to be used
     * within repository.
     * @test
     */
    public function canInjectArbitraryEntityManager()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $repo->setEntityManager($em);
        $this->assertEquals($em, $repo->getEntityManager());
    }


    /**
     * Test that we do throw proper exception on database errors when saving
     * an item.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DatabaseException
     * @expectedExceptionMessage Database error
     */
    public function throwExceptionOnDatabaseErrorWhenSaving()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist')->andThrow('Exception');

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $repo->setEntityManager($em);

        $item = Mockery::mock('ShiftContentNew\Item\Item');
        $repo->save($item);
    }


    /**
     * Test that we are able to save item
     * @test
     */
    public function canSaveItem()
    {
        $item = new Item;
        $item->setPublicationDate(new \DateTime);
        $item->setTitle('Example item');
        $item->setSlug('test-slug');

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $result = $repo->save($item);

        $this->assertInstanceOf('ShiftContentNew\Item\Item', $result);
        $this->assertNotNull($item->getId());
    }


    /**
     * Test that we are able to save item with content type set.
     * @test
     */
    public function canSaveItemWithContentType()
    {
        $type = new Type(array('name' => 'Test content type'));
        $typeRepo = $this->em->getRepository('ShiftContentNew\Type\Type');
        $typeRepo->save($type);

        $item = new Item;
        $item->setPublicationDate(new \DateTime);
        $item->setTitle('Example item');
        $item->setSlug('test-slug');
        $item->setContentType($type);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $itemId = $repo->save($item)->getId();

        //clear
        unset($item, $type);
        $this->em->clear();

        $item = $repo->findOneById($itemId);
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Type',
            $item->getContentType()
        );
    }


    /**
     * Test that we throw proper exception on database errors when
     * deleting items.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DatabaseException
     * @expectedExceptionMessage Database error
     */
    public function throwExceptionOnDatabaseErrorWhenDeleting()
    {
        $em = Mockery::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('remove')->andThrow('Exception');

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $repo->setEntityManager($em);

        $item = Mockery::mock('ShiftContentNew\Item\Item');
        $repo->delete($item);
    }


    /**
     * Test that we are able to delete an item
     * @test
     */
    public function canDeleteItem()
    {
        $item = new Item;
        $item->setPublicationDate(new \DateTime);
        $item->setTitle('Example item');
        $item->setSlug('test-slug');

        //save first
        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');

        $repo->save($item);
        $savedId = $item->getId();
        $this->assertNotNull($savedId);
        $this->em->clear();
        unset($item);

        //now find and delete
        $item = $repo->findOneById($savedId);
        $this->assertInstanceOf('ShiftContentNew\Item\Item', $item);
        $repo->delete($item);
        $this->assertNull($item->getId());
        $this->assertNull($repo->findOneById($savedId));
    }


    /**
     * Test that we are able to save an item with some custom properties.
     * @test
     */
    public function canSaveItemWithCustomProperties()
    {
        $item = new Item;
        $item->setPublicationDate(new \DateTime);
        $item->setTitle('Example item');
        $item->setSlug('test-slug');

        $property = new \ShiftContentNew\FieldValue\String;
        $property->setName('text');
        $property->setValue('Some text value');
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        //clear em
        unset($item, $property);
        $this->em->clear();

        $item = $repo->findOneById($savedId);
        $property = $item->getPropertyByName('text');

        $this->assertNotNull($item->getId());
        $this->assertNotNull($property);
        $this->assertNotNull($property->getId());
    }


    /**
     * Test that deleting items also deletes all its properties by relation.
     * @test
     */
    public function deletingItemDeletesItsProperties()
    {
        $item = new Item;
        $item->setPublicationDate(new \DateTime);
        $item->setTitle('Example item');
        $item->setSlug('test-slug');

        $property = new \ShiftContentNew\FieldValue\String;
        $property->setName('text');
        $property->setValue('Some text value');
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $saveId = $repo->save($item)->getId();

        //clear em
        unset($item, $property);
        $this->em->clear();

        //now find and delete
        $item = $repo->findOneById($saveId);
        $repo->delete($item);

        //clear em
        unset($item, $property);
        $this->em->clear();

        $items = $repo->findAll();
        $this->assertEmpty($items);

        $propertiesRepo = $this->em->getRepository(
            'ShiftContentNew\FieldValue\AbstractFieldValue'
        );
        $properties = $propertiesRepo->findAll();
        $this->assertTrue(empty($properties));
    }








}//class ends here