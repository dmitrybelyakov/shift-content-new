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
namespace ShiftTest\Unit\ShiftContentNew\Type;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\TypeService;
use ShiftContentNew\Type\Type;


/**
 * Type service test
 * This holds unit tests for content type service functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class TypeServiceTest extends TestCase
{
    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();
    }

    // ------------------------------------------------------------------------

    /*
     * Dependencies test
     */

    /**
     * Test that we are able to inject arbitrary repository to be used within
     * the service.
     * @test
     */
    public function canInjectArbitraryRepository()
    {
        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $service = new TypeService($this->sm());
        $service->setRepository($repo);
        $this->assertEquals($repo, $service->getRepository());
    }


    /**
     * Test that we do obtain repository from entity manager if none is
     * injected.
     * @test
     */
    public function getRepositoryFromDoctrineIfNoneInjected()
    {
        $service = new TypeService($this->sm());
        $repo = $service->getRepository();
        $this->assertInstanceOf('ShiftContentNew\Type\TypeRepository', $repo);
    }


    // ------------------------------------------------------------------------

    /*
     * Public API tests
     */

    /**
     * Test that we can obtain service from locator.
     * @test
     */
    public function canGetServiceFromLocator()
    {
        $serviceName = 'ShiftContentNew\Type\TypeService';
        $service = $this->sm()->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }


    /**
     * Test that we can obtain a list of all types
     * @test
     */
    public function canGetAllTypes()
    {
        $types = array('we are content types');
        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $repo->shouldReceive('findAll')->andReturn($types);

        $service = new TypeService($this->sm());
        $service->setRepository($repo);
        $this->assertEquals($types, $service->getTypes());
    }


    /**
     * Test tha we are able to get type by id.
     * @test
     */
    public function canGetTypeById()
    {
        $id = 123;
        $type = Mockery::mock('ShiftContentNew\Type\Type');

        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $repo->shouldReceive('findOneById')
            ->with($id)
            ->andReturn($type);


        $service = new TypeService($this->sm());
        $service->setRepository($repo);
        $this->assertEquals($type, $service->getType($id));
    }


    /**
     * Test that we are able to find type by name.
     * @test
     */
    public function canGetTypeByName()
    {
        $name = 'Type name';
        $type = Mockery::mock('ShiftContentNew\Type\Type');

        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $repo->shouldReceive('findOneByName')
            ->with($name)
            ->andReturn($type);


        $service = new TypeService($this->sm());
        $service->setRepository($repo);
        $this->assertEquals($type, $service->getTypeByName($name));
    }


    /**
     * Test that we return validation result object if content type fails
     * validation when saving.
     * @test
     */
    public function returnErrorsIfTypeFailsValidationOnSave()
    {
        //mock validator
        $result = Mockery::mock('ShiftCommon\Model\Validation\Result');
        $result->shouldReceive('isValid')->andReturn(false);
        $validator = Mockery::mock('ShiftContent\Type\TypeValidator');
        $validator->shouldReceive('validate')->andReturn($result);

        //mock service manager
        $sm = Mockery::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')
            ->with('ShiftContentNew\Type\TypeValidator')
            ->andReturn($validator);

        $service = new TypeService($sm);
        $this->assertEquals($result, $service->saveType(new Type));
    }


    /**
     * Test that we are able to persist valid type.
     * @test
     */
    public function canSaveValidType()
    {
        //mock validator
        $result = Mockery::mock('ShiftCommon\Model\Validation\Result');
        $result->shouldReceive('isValid')->andReturn(true);
        $validator = Mockery::mock('ShiftContent\Type\TypeValidator');
        $validator->shouldReceive('validate')->andReturn($result);

        //mock service manager
        $sm = Mockery::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')
            ->with('ShiftContentNew\Type\TypeValidator')
            ->andReturn($validator);

        $saveResult = 'type saved';
        $type = new Type();
        $type->setName('A content type');

        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $repo->shouldReceive('save')->andReturn($saveResult);

        $service = new TypeService($sm);
        $service->setRepository($repo);
        $result = $service->saveType($type);
        $this->assertEquals($saveResult, $result);

    }


    /**
     * Test that we are able to delete type.
     * @test
     */
    public function canDeleteType()
    {
        $deleteResult = 'type saved';
        $type = Mockery::mock('ShiftContentNew\Type\Type');
        $repo = Mockery::mock('ShiftContentNew\Type\TypeRepository');
        $repo->shouldReceive('delete')->andReturn($deleteResult);

        $service = new TypeService($this->sm());
        $service->setRepository($repo);
        $this->assertEquals($deleteResult, $service->deleteType($type));
    }






}//class ends here