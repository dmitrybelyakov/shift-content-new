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
use ShiftContentNew\FieldValue\Date;
use ShiftContentNew\FieldValue\Text;
use ShiftContentNew\FieldValue\String;
use ShiftContentNew\FieldValue\MediaAlbum;
use ShiftContentNew\FieldValue\MediaItem;

/**
 * This holds integration tests mapping of content items to field values.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 */
class ValuesMappingTest extends TestCase
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
     * Test that we are able to use string field values in content items.
     * @test
     */
    public function canUseStringFieldValues()
    {
        $value = 'A text string';

        $item = new Item(array(
            'title' => 'Test item',
            'slug' => 'test-slug',
            'publicationDate' => new \Datetime,
        ));

        //add property
        $property = new String(array(
            'name' => 'property',
            'value' => $value
        ));
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        $this->em->clear();
        $item = $repo->findOneById($savedId);
        $this->assertEquals($value, $item->getProperty());
    }


    /**
     * Test that we are able to use text field values in content items.
     * @test
     */
    public function canUseTextFieldValues()
    {
        $value = 'Promotion an ourselves up otherwise my. High what each snug';
        $value .= 'rich far yet easy. In companions inhabiting mr principles ';
        $value .= 'insensible do. Heard their sex hoped enjoy vexed child ';
        $value .= 'Prosperous so occasional assistance it discovered ';
        $value .= 'Provision of he residence consisted up in remainder ';
        $value .= 'described. Conveying has concealed necessary furnished ';
        $value .= 'zealously immediate get but. Terminated as middletons or ';
        $value .= 'instrument. Bred do four so your felt with. No shameless ';
        $value .= 'dependent household do.';

        $item = new Item(array(
            'title' => 'Test item',
            'slug' => 'test-slug',
            'publicationDate' => new \Datetime,
        ));

        //add property
        $property = new Text(array(
            'name' => 'property',
            'value' => $value
        ));
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        $this->em->clear();
        $item = $repo->findOneById($savedId);
        $this->assertEquals($value, $item->getProperty());
    }


    /**
     * Test that we are able to use date field values in content items.
     * @test
     */
    public function canUseDateFieldValues()
    {
        $value = '12-12-2012 12:12:12';

        $item = new Item(array(
            'title' => 'Test item',
            'slug' => 'test-slug',
            'publicationDate' => new \Datetime,
        ));

        //add property
        $property = new Date(array(
            'name' => 'property',
            'value' => $value
        ));
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        $this->em->clear();
        $item = $repo->findOneById($savedId);
        $this->assertInstanceOf('DateTime', $item->getProperty());
        $this->assertEquals(
            $value,
            $item->getProperty()->format('d-m-Y H:i:s')
        );
    }


    /**
     * Test that we are able to use media item field values in content items.
     * @test
     */
    public function canUseMediaItemFieldValues()
    {
        //create media item
        $mediaItemClass = 'ShiftMedia\Item\Item';
        if(!class_exists($mediaItemClass))
            $this->markTestSkipped('ShiftMedia module not found');

        $mediaItem = new $mediaItemClass(array(
            'title' => 'test item',
            'mime' => 'img/jpg',
            'extension' => 'jpg',
        ));

        $mediaRepo = $this->em->getRepository($mediaItemClass);
        $mediaRepo->save($mediaItem);

        //now create content item
        $value = $mediaItem;

        $item = new Item(array(
            'title' => 'Test item',
            'slug' => 'test-slug',
            'publicationDate' => new \Datetime,
        ));

        //add property
        $property = new MediaItem(array(
            'name' => 'property',
            'value' => $value
        ));
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        $this->em->clear();
        $item = $repo->findOneById($savedId);

        $this->assertInstanceOf(
            'ShiftMedia\Item\Item',
            $item->getProperty()
        );
    }


    /**
     * Test that we are able to use media album field values in content items.
     * @test
     */
    public function canUseMediaAlbumFieldValues()
    {
        $albumClass = 'ShiftMedia\Album\Album';
        if(!class_exists($albumClass))
            $this->markTestSkipped('ShiftMedia module not found');

        //create media item
        $mediaItemClass = 'ShiftMedia\Item\Item';
        $mediaItem = new $mediaItemClass(array(
            'title' => 'test item',
            'mime' => 'img/jpg',
            'extension' => 'jpg',
        ));

        //create media album
        $mediaAlbumClass = 'ShiftMedia\Album\Album';
        $mediaAlbum = new $mediaAlbumClass(array('title' => 'Test album'));
        $mediaAlbum->addItem($mediaItem);

        $mediaAlbumRepo = $this->em->getRepository($mediaAlbumClass);
        $mediaAlbumRepo->save($mediaAlbum);

        //now create content item
        $value = $mediaAlbum;

        $item = new Item(array(
            'title' => 'Test item',
            'slug' => 'test-slug',
            'publicationDate' => new \Datetime,
        ));

        //add property
        $property = new MediaAlbum(array(
            'name' => 'property',
            'value' => $value
        ));
        $item->addProperty($property);

        $repo = $this->em->getRepository('ShiftContentNew\Item\Item');
        $savedId = $repo->save($item)->getId();

        $this->em->clear();
        $item = $repo->findOneById($savedId);

        $this->assertInstanceOf(
            'ShiftMedia\Album\Album',
            $item->getProperty()
        );

        $mediaItem = array_shift($item->getProperty()->getItems(1,1));
        $this->assertInstanceOf('ShiftMedia\Item\Item', $mediaItem);
    }



}//class ends here