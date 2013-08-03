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
namespace ShiftTest\Unit\ShiftContentNew\FieldType\File;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\FieldType\File\FileSettings;
use ShiftContentNew\FieldType\File\FileSettingsValidator;

/**
 * File settings validator test
 * This holds unit tests for file settings validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class FileSettingsValidatorTest extends TestCase
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
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {

        $validator = new FileSettingsValidator($this->getLocator());
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\File\FileSettingsValidator',
            $validator
        );
    }


    /**
     * Test that we can validate settings and fail.
     * @test
     */
    public function canValidateSettingsAndFail()
    {
        $settings = new FileSettings;
        $settings->setMode('BAD');

        $validator = new FileSettingsValidator($this->getLocator());
        $result = $validator->validate($settings);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['destination']));
        $this->assertTrue(isset($errors['mode']));
    }


    /**
     * Test that we can validate settings and pass if everything is correct.
     * @test
     */
    public function canValidateSettingsAndPass()
    {
        $settings = new FileSettings;
        $settings->setDestination('data/temp/will-be-created');

        $validator = new FileSettingsValidator($this->getLocator());
        $result = $validator->validate($settings);
        $this->assertTrue($result->isValid());
    }


}//class ends here