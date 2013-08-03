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
namespace ShiftTest\Integration\ShiftContentNew;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Item\Item;
use ShiftContentNew\ContentService;
use ShiftContentNew\Type\Field\FieldFactory;

/**
 * Content service test
 * This holds integration tests for content service.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
 * @group contentUpdate
 */
class ContentServiceTest extends TestCase
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
     * Test that we are able to retrieve content service from locator
     * @test
     */
    public function canGetServiceFromLocator()
    {
        $serviceName = 'ShiftContentNew\ContentService';
        $service = $this->getLocator()->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }


}//class ends here