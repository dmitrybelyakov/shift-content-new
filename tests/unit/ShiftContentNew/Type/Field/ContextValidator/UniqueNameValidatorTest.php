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

use ShiftContentNew\Type\Field\ContextValidator\UniqueNameValidator;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Type\Type;


/**
 * Unique field validator test
 * This holds unit tests for unique field name context validator.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class UniqueNameValidatorTest extends TestCase
{

    /**
     * Test that we can instantiate validator.
     * @test
     */
    public function canInstantiateValidator()
    {
        $validator = new UniqueNameValidator;
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\ContextValidator\UniqueNameValidator',
            $validator
        );
    }


    /**
     * Test that we do throw an exception when trying to validate a field
     * that is of wrong type.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\DomainException
     * @expectedExceptionMessage Field must be of
     */
    public function throwExceptionWhenValidatingFieldOfBadType()
    {
        $validator = new UniqueNameValidator;
        $validator->isValid('BAD-OBJECT', new Type);
    }


    /**
     * Test that field name unique in parent content type fields collection
     * passes validation.
     * @test
     */
    public function uniqueFieldNamePassesValidation()
    {
        $field1 = new Field(array('name' => 'First field'));
        $field2 = new Field(array('name' => 'Second field'));

        $type = new Type;
        $type->addField($field1);
        $type->addField($field2);

        $validator = new UniqueNameValidator;
        $this->assertTrue($validator->isValid($field1, $type));
    }


    /**
     * Test that field name not unique in content type fields collection
     * fails validation.
     *
     * @test
     */
    public function nonUniqueFieldNameFailsValidation()
    {
        $field1 = new Field(array('name' => 'A field'));
        $field2 = new Field(array('name' => 'A field')); //not the same object

        $type = new Type;
        $type->addField($field1);
        $type->addField($field2);

        $validator = new UniqueNameValidator;
        $this->assertFalse($validator->isValid($field1, $type));

        $errors = $validator->getMessages();
        $this->assertTrue(isset($errors['nameNotUnique']));
    }






}//class ends here