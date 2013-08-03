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

use ShiftContentNew\Item\Validator\DateValidator;

/**
 * Item date validator test
 * This holds unit tests for item creation/publication date validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class DateValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Item\Validator\DateValidator';
        $validator = new DateValidator;
        $this->assertInstanceOf($type, $validator);
    }


    /**
     * Test that invalid date fails validation.
     * @test
     */
    public function invalidDateFailsValidation()
    {
        $date = 'INVALID';
        $validator = new DateValidator;

        $this->assertFalse($validator->isValid($date));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['itemDateInvalid']));
    }


    /**
     * Test that null value fails validation.
     * @test
     */
    public function nullValueFailsValidation()
    {
        $date = null;
        $validator = new DateValidator;

        $this->assertFalse($validator->isValid($date));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['itemDateInvalid']));
    }


    /**
     * Test that date time not in UTC fails validation.
     * @test
     */
    public function nonUtcDateFailsValidation()
    {
        $date = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $validator = new DateValidator;

        $this->assertFalse($validator->isValid($date));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['itemDateNotUtc']));
    }

    /**
     * Test that valid date passes validation
     */
    public function validDatePassesValidation()
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $validator = new DateValidator;

        $this->assertTrue($validator->isValid($date));

        $errors = $validator->getMessages();
        $this->assertTrue(empty($errors));
    }


}//class ends here