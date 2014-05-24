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
namespace ShiftTest\Unit\ShiftContentNew\Type;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\TypeValidator;

/**
 * Content type validator
 * This holds unit tests for content type entity validator.
 *
 * SEE: TypeAggregateValidationTest integration test for end-to-end
 * aggregate validation testing.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class TypeValidatorTest extends TestCase
{
    /**
     * Test that we can instantiate validator
     * @test
     */
    public function canInstantiateType()
    {
        $validator = new TypeValidator($this->sm());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\TypeValidator',
            $validator
        );
    }


    /**
     * Test that we are able to validate content type and pass if it's valid.
     * @test
     */
    public function canValidateTypeAndPass()
    {
        $type = new Type(array('name' => 'A test type'));
        $validator = new TypeValidator($this->sm());
        $result = $validator->validate($type);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we are able to validate content type and fail if it's invalid.
     * @test
     */
    public function canValidateTypeAndFail()
    {
        $type = new Type;
        $validator = new TypeValidator($this->sm());
        $result = $validator->validate($type);
        $this->assertFalse($result->isValid());

        $errors = $result->getErrors();
        $this->assertTrue(isset($errors['name']));
    }




}//class ends here