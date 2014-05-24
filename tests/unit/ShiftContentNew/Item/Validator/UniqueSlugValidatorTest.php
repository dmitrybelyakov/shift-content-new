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
namespace ShiftTest\Unit\ShiftContentNew\Item\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Item\Validator\UniqueSlugValidator;

/**
 * Unique slug validator test
 * This holds unit tests for item slug uniqueness validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class UniqueSlugValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Item\Validator\UniqueSlugValidator';
        $validator = new UniqueSlugValidator;
        $this->assertInstanceOf($type, $validator);
    }

    /**
     * Test that we are able to inject arbitrary service locator
     * @test
     */
    public function canInjectServiceLocator()
    {
        $locator = Mockery::mock('Zend\Di\Di');
        $validator = new UniqueSlugValidator;
        $validator->setLocator($locator);
        $this->assertEquals($locator, $validator->getLocator());
    }


    /**
     * Test that we are able to inject arbitrary content service into validator
     * @test
     */
    public function canInjectContentService()
    {
        $contentService = Mockery::mock('ShiftContentNew\ContentService');
        $validator = new UniqueSlugValidator;
        $validator->setContentService($contentService);
        $this->assertEquals($contentService, $validator->getContentService());
    }


    /**
     * Test that we get content service from locator if it is not
     * explicitly injected.
     * @test
     */
    public function obtainContentServiceFromLocatorIfNoneInjected()
    {
        $validator = new UniqueSlugValidator;
        $validator->setLocator($this->sm());
        $contentService = $validator->getContentService();
        $this->assertInstanceOf(
            'ShiftContentNew\ContentService',
            $contentService
        );
    }


    /**
     * Test that existing (not unique) slug fails validation
     * @test
     */
    public function existingSlugFailsValidation()
    {
        $slug = 'i-exist';

        $foundItem = Mockery::mock('ShiftContentNew\Item\Item');

        //mock service
        $service = Mockery::mock('ShiftContentNew\ContentService');
        $service->shouldReceive('getItemBySlug')
            ->with($slug)
            ->andReturn($foundItem);

        $validator = new UniqueSlugValidator;
        $validator->setContentService($service);
        $this->assertFalse($validator->isValid($slug));

        $errors = $validator->getmessages();
        $this->assertTrue(isset($errors['slugNotUnique']));
    }


    /**
     * Test that nonexistent slug (unique) passes validation.
     * @test
     */
    public function nonexistentSlugPassesValidation()
    {
        $slug = 'i-exist';

        //mock service
        $service = Mockery::mock('ShiftContentNew\ContentService');
        $service->shouldReceive('getItemBySlug')->with($slug);

        $validator = new UniqueSlugValidator;
        $validator->setContentService($service);
        $this->assertTrue($validator->isValid($slug));
    }


    /**
     * Test that if we have an item as our context and slug is the same
     * we pass validation.
     * @test
     */
    public function slugSameAsInItemBeingEditedPassesValidation()
    {
        $slug = 'i-am-being-edited';

        //mock edited item (context)
        $editedItem = Mockery::mock('ShiftContentNew\Item\Item');
        $editedItem->shouldReceive('getSlug')->andReturn($slug);

        //mock found item
        $foundItem = Mockery::mock('ShiftContentNew\Item\Item');

        //mock service
        $service = Mockery::mock('ShiftContentNew\ContentService');
        $service->shouldReceive('getItemBySlug')
            ->with($slug)
            ->andReturn($foundItem);


        $validator = new UniqueSlugValidator;
        $validator->setContentService($service);
        $this->assertTrue($validator->isValid($slug, $editedItem));
    }


}//class ends here