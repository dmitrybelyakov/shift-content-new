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

use ShiftContentNew\Type\Field\Attribute\Validator\AttributeClassValidator;

/**
 * Attribute class validator test
 * This holds unit tests for attribute class validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeClassValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $type .= '\AttributeClassValidator';

        $validator = new AttributeClassValidator;
        $this->assertInstanceOf($type, $validator);
    }


    /**
     * Test that invalid class fails validation.
     * @test
     */
    public function invalidTypeFailsValidation()
    {
        $type = 'INVALID';
        $validator = new AttributeClassValidator;

        $this->assertFalse($validator->isValid('Nonexistent'));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['attributeClassInvalid']));
    }

    /**
     * Test that valid class passes validation
     */
    public function validTypePassesValidation()
    {
        $validator = new AttributeClassValidator;

        //any existing class will work
        $this->assertTrue($validator->isValid('Exception'));

        $errors = $validator->getMessages();
        $this->assertTrue(empty($errors));
    }


}//class ends here