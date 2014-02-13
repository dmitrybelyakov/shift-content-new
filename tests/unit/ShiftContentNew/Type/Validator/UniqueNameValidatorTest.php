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
namespace ShiftTest\Unit\ShiftContentNew\Type\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Validator\UniqueNameValidator;


/**
 * Unique name test
 * This holds unit tests for unique content type name validator
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class UniqueNameValidatorTest extends TestCase
{
    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new UniqueNameValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Validator\UniqueNameValidator',
            $validator
        );
    }


    /**
     * Test that existing content type name fails validation.
     * @test
     */
    public function existingNameFailsValidation()
    {
        $name = 'i-exist';

        //mock found type
        $foundType = Mockery::mock('ShiftContentNew\Type\Type');

        //mock service
        $service = Mockery::mock('ShiftContentNew\Type\TypeService');
        $service->shouldReceive('getTypeByName')
            ->with($name)
            ->andReturn($foundType);

        //mock locator
        $locator = Mockery::mock('Zend\Di\Di');
        $locator->shouldReceive('get')
            ->with('ShiftContentNew\Type\TypeService')
            ->andReturn($service);

        //validate
        $validator = new UniqueNameValidator;
        $validator->setLocator($locator);
        $this->assertFalse($validator->isValid($name));
    }


    /**
     * Test that nonexistent content type passes validation.
     * @test
     */
    public function nonexistentNamePassesValidation()
    {
        $name = 'i-dont-exist';

        //mock service
        $service = Mockery::mock('ShiftContentNew\Type\TypeService');
        $service->shouldReceive('getTypeByName')->with($name);

        //mock locator
        $locator = Mockery::mock('Zend\Di\Di');
        $locator->shouldReceive('get')
            ->with('ShiftContentNew\Type\TypeService')
            ->andReturn($service);

        //validate
        $validator = new UniqueNameValidator;
        $validator->setLocator($locator);
        $this->assertTrue($validator->isValid($name));
    }

    /**
     * Test that same content type name as the one passed as context
     * passes validation.
     * @test
     */
    public function ContentTypeNameSameAsContextPassesValidation()
    {
        $name = 'im-being-edited';

        //mock context
        $editedType = Mockery::mock('ShiftContentNew\Type\Type');
        $editedType->shouldReceive('getName')->andReturn($name);
        $editedType->shouldReceive('getId')->andReturn(123);

        //mock found type
        $foundType = Mockery::mock('ShiftContentNew\Type\Type');

        //mock service
        $service = Mockery::mock('ShiftContentNew\Type\TypeService');
        $service->shouldReceive('getTypeByName')
            ->with($name)
            ->andReturn($foundType);

        //mock locator
        $locator = Mockery::mock('Zend\Di\Di');
        $locator->shouldReceive('get')
            ->with('ShiftContentNew\Type\TypeService')
            ->andReturn($service);

        //validate
        $validator = new UniqueNameValidator;
        $validator->setLocator($locator);
        $this->assertTrue($validator->isValid($name, $editedType));
    }







}//class ends here