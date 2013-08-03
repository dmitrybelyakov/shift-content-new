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
namespace ShiftTest\Unit\ShiftContentNew\FieldType\File\Validator;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\FieldType\File\Validator\ModeValidator;

/**
 * Mode validator tests
 * This holds unit tests for file upload mode setting validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class ModeValidatorTest extends TestCase
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
     * Test that we can instantiate settings.
     * @test
     */
    public function canInstantiateSettings()
    {

        $validator = new ModeValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\File\Validator\ModeValidator',
            $validator
        );
    }


    /**
     * Test that invalid upload mode fails validation.
     * @test
     */
    public function invalidModeFailsValidation()
    {
        $validator = new ModeValidator;
        $this->assertFalse($validator->isValid('BAD'));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['uplaodModeInvalid']));
    }


    /**
     * Test that valid upload mode passes validation.
     * @test
     */
    public function validModePassesValidation()
    {
        $validator = new ModeValidator;
        $this->assertTrue($validator->isValid('uploadToDestination'));
        $this->assertTrue($validator->isValid('createFolder'));
    }


}//class ends here