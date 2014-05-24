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
use ShiftContentNew\Type\TypeApiService;
use ShiftTest\TestCase;


/**
 * Type api service test
 * This holds unit tests for content type api service functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class TypeApiServiceTest extends TestCase
{
    /**
     * Set up environment to run our tests
     */
    public function setUp()
    {
        parent::setUp();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can obtain service from locator.
     * @test
     */
    public function canGetServiceFromLocator()
    {
        $serviceName = 'ShiftContentNew\Type\TypeApiService';
        $service = $this->sm()->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }


    /**
     * Test that we are able to inject arbitrary type service.
     * @test
     */
    public function canInjectArbitraryTypeService()
    {
        $typeService = Mockery::mock('ShiftContentNew\Type\TypeService');
        $service = new TypeApiService($this->sm());
        $service->setTypeService($typeService);
        $this->assertEquals($typeService, $service->getTypeService());
    }


    /**
     * Test that we do obtain type service from locator if none injected.
     * @test
     */
    public function getTypeServiceFromLocatorIfNoneInjected()
    {
        $service = new TypeApiService($this->sm());
        $typeService = $service->getTypeService();
        $this->assertInstanceOf(
            'ShiftContentNew\Type\TypeService',
            $typeService
        );
    }





}//class ends here