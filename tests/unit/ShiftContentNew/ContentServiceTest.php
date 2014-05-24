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
namespace ShiftTest\Unit\ShiftContentNew;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\ContentService;


/**
 * Content service test
 * This holds unit tests for content service functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class ContentServiceTest extends TestCase
{
    /**
     * Test that we can obtain service from locator.
     * @test
     */
    public function canGetServiceFromLocator()
    {
        $serviceName = 'ShiftContentNew\ContentService';
        $service = $this->sm()->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }


    /**
     * Test that we are able to inject arbitrary repository to be used within
     * the service.
     * @test
     */
    public function canInjectArbitraryRepository()
    {
        $repo = Mockery::mock('ShiftContentNew\Item\ItemRepository');
        $service = new ContentService($this->sm());
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
        $service = new ContentService($this->sm());
        $repo = $service->getRepository();
        $this->assertInstanceOf('ShiftContentNew\Item\ItemRepository', $repo);
    }







}//class ends here