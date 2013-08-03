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

use ShiftContentNew\Type\TypeService;
use ShiftContentNew\Type\Type;

/**
 * Type service test
 * This holds integration tests for content type service.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Test
 *
 * @group       integration
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



    /**
     * Test that we are able to validate a simple content type
     * without any fields.
     * @test
     */
    public function canValidateContentType()
    {
        $type = new Type;
        $service = new TypeService($this->getLocator());
        $service->validateTypeAggregate($type);
    }







}//class ends here