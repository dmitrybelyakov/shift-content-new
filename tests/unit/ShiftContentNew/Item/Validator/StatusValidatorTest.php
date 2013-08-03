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

use ShiftContentNew\Item\Validator\StatusValidator;

/**
 * Item status validator test
 * This holds unit tests for item status validator functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class StatusValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Item\Validator\StatusValidator';
        $validator = new StatusValidator;
        $this->assertInstanceOf($type, $validator);
    }


    /**
     * Test that invalid status fails validation.
     * @test
     */
    public function invalidTypeFailsValidation()
    {
        $status = 'INVALID';
        $validator = new StatusValidator;

        $this->assertFalse($validator->isValid($status));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['itemStatusInvalid']));
    }

    /**
     * Test that valid type passes validation
     */
    public function validTypePassesValidation()
    {
        $validator = new StatusValidator;

        $this->assertTrue($validator->isValid('published'));
        $this->assertTrue($validator->isValid('pending'));
        $this->assertTrue($validator->isValid('draft'));
        $this->assertTrue($validator->isValid('deleted'));

        $errors = $validator->getMessages();
        $this->assertTrue(empty($errors));
    }


}//class ends here