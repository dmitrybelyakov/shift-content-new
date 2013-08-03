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
namespace ShiftTest\Unit\ShiftContentNew\Item;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Item\Item;

/**
 * Content item metadata tests
 * This holds unit tests for content item metadata entity.
 * This functionality is common for all content items.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class MetaTest extends TestCase
{

    /**
     * Content item class name
     * @var string
     */
    protected $entityClass = 'ShiftContentNew\Item\Meta';

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

        //prepare data for testing
        $this->data = array(
            'publicationDate' => '12-12-2012',
            'title' => 'Test item title',
            'slug' => 'test-item'
        );

    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can populate item with data upon instantiation.
     * @test
     */
    public function canPopulateWithDataUponInstantiation()
    {
        $data = $this->data;
        $item = new Item($data);

        $this->assertEquals('draft', $item->getStatus());
        $this->assertEquals($item->getTitle(), $data['title']);
        $this->assertEquals($item->getSlug(), $data['slug']);
        $this->assertEquals(
            $item->getPublicationDate(),
            new \DateTime($data['publicationDate'], new \DateTimezone('UTC'))
        );
    }


    /**
     * Test that we can get item id.
     * @test
     */
    public function canGetId()
    {
        $item = new Item;
        $this->assertNull($item->getId());
    }


    /**
     * Test that we can set item author.
     * @test
     */
    public function canSetAuthor()
    {
        $author = Mockery::mock('ShiftUser\User');
        $item = new Item;
        $item->setAuthor($author);
        $this->assertEquals($author, $item->getAuthor());
    }


    /**
     * Test that we are able to set parent content type for an item
     * @test
     */
    public function canSetContentType()
    {
        $type = Mockery::mock('ShiftContentNew\Type\Type');
        $item = new Item;
        $item->setContentType($type);
        $this->assertEquals($type, $item->getContentType());
    }


    /**
     * Test that we are able to mark item as pending.
     * @test
     */
    public function canMarkPending()
    {
        $item = new Item;
        $this->assertTrue($item->isDraft());

        $item->markPending();
        $this->assertTrue($item->isPending());
        $this->assertEquals('pending', $item->getStatus());
    }

    /**
     * Test that we are able to mark item as a draft.
     * @test
     */
    public function canMarkAsDraft()
    {
        $item = new Item($this->data);

        $item->markPending();
        $this->assertFalse($item->isDraft());

        $item->markDraft();
        $this->assertTrue($item->isDraft());
        $this->assertEquals('draft', $item->getStatus());
    }


    /**
     * Test that we are able to mark item as published.
     * @test
     */
    public function canMarkAsPublished()
    {
        $item = new Item($this->data);
        $this->assertTrue($item->isDraft());

        $item->markPublished();
        $this->assertTrue($item->isPublished());
        $this->assertEquals('published', $item->getStatus());
    }


    /**
     * Test that we are able to mark item as deleted.
     * @test
     */
    public function canMarkAsDeleted()
    {
        $item = new Item($this->data);
        $this->assertTrue($item->isDraft());

        $item->markDeleted();
        $this->assertTrue($item->isDeleted());
        $this->assertEquals('deleted', $item->getStatus());
    }


    /**
     * Test that we can get item creation date.
     * @test
     */
    public function canGetCreated()
    {
        $item = new Item;
        $this->assertInstanceOf('DateTime', $item->getCreated());
    }


    /**
     * Test that we can set item publication date as string.
     * @test
     */
    public function canSetPublicationDateAsString()
    {
        $date = '12-12-2012 12:12:12';
        $item = new Item($this->data);
        $item->setPublicationDate($date);
        $this->assertEquals(
            'UTC',
            $item->getPublicationDate()->getTimezone()->getName()
        );
    }

    /**
     * Test that we do convert item publication date to UTC if its not.
     * @test
     */
    public function convertPublicationToUtcUponSetting()
    {
        $date = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $item = new Item($this->data);
        $item->setPublicationDate($date);
        $this->assertEquals(
            'UTC',
            $item->getPublicationDate()->getTimezone()->getName()
        );

    }

    /**
     * Test that we can set item title.
     * @test
     */
    public function canSetItemTitle()
    {
        $title = 'Test title';
        $item = new Item;
        $item->setTitle($title);
        $this->assertEquals($title, $item->getTitle());
    }


    /**
     * Test that we can set item slug.
     * @test
     */
    public function canSetItemSlug()
    {
        $slug = 'test-item-slug';
        $item = new Item;
        $item->setSlug($slug);
        $this->assertEquals($slug, $item->getSlug());
    }


    /**
     * Test that we can get composed view model from content
     * @test
     */
    public function canGetViewModel()
    {
        $template = 'example/template';
        $item = new Item;
        $vm = $item->getViewModel();

        //assert content variables set to view model
        $variables = $vm->getVariables();
        $this->assertTrue(is_array($variables));
    }







}//class ends here