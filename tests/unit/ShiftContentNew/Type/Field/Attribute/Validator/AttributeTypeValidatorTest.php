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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Attribute\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Attribute\Validator\AttributeTypeValidator;

/**
 * Attribute type validator test
 * This holds unit tests for attribute type validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeTypeValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $type .= '\AttributeTypeValidator';

        $validator = new AttributeTypeValidator;
        $this->assertInstanceOf($type, $validator);
    }


    /**
     * Test that invalid type fails validation.
     * @test
     */
    public function invalidTypeFailsValidation()
    {
        $type = 'INVALID';
        $validator = new AttributeTypeValidator;

        $this->assertFalse($validator->isValid($type));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['attributeTypeInvalid']));
    }

    /**
     * Test that valid type passes validation
     */
    public function validTypePassesValidation()
    {
        $validator = new AttributeTypeValidator;

        $this->assertTrue($validator->isValid('filter'));
        $this->assertTrue($validator->isValid('validator'));

        $errors = $validator->getMessages();
        $this->assertTrue(empty($errors));
    }


}//class ends here