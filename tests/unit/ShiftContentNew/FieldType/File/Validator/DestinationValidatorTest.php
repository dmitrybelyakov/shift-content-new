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

use ShiftContentNew\FieldType\File\Validator\DestinationValidator;
use ShiftCommon\Filesystem\Filesystem;

/**
 * Destination validator tests
 * This holds unit tests for file destination settings validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 * @group contentUpdate
 */
class DestinationValidatorTest extends TestCase
{
    /**
     * Base destination for tests
     * @var string
     */
    protected $base = 'data/temp/test-content-filesettings';

    /**
     * Set up environment to run our tests
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        if(!is_dir($this->base))
            Filesystem::createDirectoryIfDoesntExist($this->base);
    }

    /**
     * Tear down environment
     * @return void
     */
    public function tearDown()
    {
        if(is_dir($this->base))
            Filesystem::recursiveDeleteDirectory($this->base);

        parent::tearDown();
    }

    // ------------------------------------------------------------------------


    /**
     * Test that we can instantiate settings.
     * @test
     */
    public function canInstantiateSettings()
    {

        $validator = new DestinationValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\FieldType\File\Validator\DestinationValidator',
            $validator
        );
    }


    /**
     * Test that validation fails if destination exists and is not writable.
     * @test
     */
    public function nonWritableDestinationFails()
    {
        //create unwritable destination
        $destination = $this->base . '/unwritable';
        mkdir($destination, 0444);

        $validator = new DestinationValidator;
        $this->assertFalse($validator->isValid($destination));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['destinationUnwritable']));
    }


    /**
     * Test that writable destination passes validation.
     * @test
     */
    public function writableDestinationPasses()
    {
        //create writable destination
        $destination = $this->base . '/writable';
        mkdir($destination, 0777);

        $validator = new DestinationValidator;
        $this->assertTrue($validator->isValid($destination));
    }


    /**
     * Test that validation fails if destination does not exist and its
     * parent is not writable.
     * @test
     */
    public function nonWritableParentDestinationFails()
    {
        //create unwritable parent
        $parent = $this->base . '/unwritable';
        $destination = $parent. '/dest';
        mkdir($parent, 0444);

        $validator = new DestinationValidator;
        $this->assertFalse($validator->isValid($destination));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['destinationParentUnwritable']));
    }




    /**
     * Test that validation fails if destination does not exist and its
     * parent does not exist as well.
     * @test
     */
    public function nonExistentParentDestinationFails()
    {
        $parent = $this->base . '/unwritable';
        $destination = $parent. '/dest';

        $validator = new DestinationValidator;
        $this->assertFalse($validator->isValid($destination));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['destinationParentNotExist']));
    }


    /**
     * Test that validation passes if destination does not exist but its
     * parent is writable.
     * @test
     */
    public function nonexistentDestinationUnderWritableParentPasses()
    {
        //create writable parent
        $parent = $this->base . '/unwritable';
        $destination = $parent. '/dest';
        mkdir($parent, 0777);

        $validator = new DestinationValidator;
        $this->assertTrue($validator->isValid($destination));
    }


}//class ends here