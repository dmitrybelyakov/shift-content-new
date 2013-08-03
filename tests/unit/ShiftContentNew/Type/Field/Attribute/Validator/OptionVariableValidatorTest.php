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

use ShiftContentNew\Type\Field\Attribute\Validator\OptionVariableValidator;

/**
 * Option variable validator
 * This one tests validation of variable name;
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class optionVariableValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateValidator()
    {
        $type = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $type .= '\OptionVariableValidator';

        $validator = new OptionVariableValidator;
        $this->assertInstanceOf($type, $validator);
    }


    /**
     * Test that valid variable name passes validation.
     * @test
     */
    public function validNamePassesValidation()
    {
        $variableName = 'validVariable';
        $validator = new OptionVariableValidator;
        $this->assertTrue($validator->isValid($variableName));
    }


    /**
     * Test that name starting with uppercase fails validation.
     * @test
     */
    public function uppercasedNameFailsValidation()
    {
        $variableName = 'MeIsInvalid';
        $validator = new OptionVariableValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['variableNameInvalid']));
    }

    /**
     * Test that spaces are not allowed.
     * @test
     */
    public function spacedNameFailsValidation()
    {
        $variableName = 'me is invalid';
        $validator = new OptionVariableValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['variableNameInvalid']));
    }

    /**
     * Test that underscores are not allowed.
     * @test
     */
    public function underscoredNameFailsValidation()
    {
        $variableName = 'invalid_variable';
        $validator = new OptionVariableValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['variableNameInvalid']));
    }

    /**
     * Test that first character can't be a number.
     * @test
     */
    public function nameStartedWithNumberFailsValidation()
    {
        $variableName = '1invalid';
        $validator = new OptionVariableValidator;
        $this->assertFalse($validator->isValid($variableName));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['variableNameInvalid']));
    }



}//class ends here