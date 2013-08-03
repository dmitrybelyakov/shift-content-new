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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Validator\FieldPropertyValidator;


/**
 * Field property validator test
 * This holds unit tests for field property validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class FieldPropertyValidatorTest extends TestCase
{


    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new FieldPropertyValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Validator\FieldPropertyValidator',
            $validator
        );
    }


    /**
     * Test that valid variable name passes validation.
     * @test
     */
    public function validNamePassesValidation()
    {
        $variableName = 'validVariable';
        $validator = new FieldPropertyValidator;
        $this->assertTrue($validator->isValid($variableName));
    }


    /**
     * Test that name starting with uppercase fails validation.
     * @test
     */
    public function uppercasedNameFailsValidation()
    {
        $variableName = 'MeIsInvalid';
        $validator = new FieldPropertyValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['invalidPropertyName']));
    }

    /**
     * Test that spaces are not allowed.
     * @test
     */
    public function spacedNameFailsValidation()
    {
        $variableName = 'me is invalid';
        $validator = new FieldPropertyValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['invalidPropertyName']));
    }

    /**
     * Test that underscores are not allowed.
     * @test
     */
    public function underscoredNameFailsValidation()
    {
        $variableName = 'invalid_variable';
        $validator = new FieldPropertyValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['invalidPropertyName']));
    }

    /**
     * Test that first character can't be a number.
     * @test
     */
    public function nameStartedWithNumberFailsValidation()
    {
        $variableName = '1invalid';
        $validator = new FieldPropertyValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['invalidPropertyName']));
    }


}//class ends here