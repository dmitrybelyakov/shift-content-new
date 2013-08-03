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
namespace ShiftTest\Unit\ShiftContentNew\Type\Field\Attribute;
use Mockery;
use ShiftTest\TestCase;

use ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator;
use ShiftContentNew\Type\Field\Attribute\AttributeOption;


/**
 * Attribute option validator test
 * Tests attribute option validator functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeOptionValidatorTest extends TestCase
{

    /**
     * Test data to populate item
     * @var array
     */
    protected $data = array();

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
        $validator = new AttributeOptionValidator($this->getLocator());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator',
            $validator
        );
    }


    /**
     * Test that we can successfully validate an option.
     * @test
     */
    public function canValidateOptionAndPass()
    {
        $attribute = 'ShiftContentNew\Type\Field\Attribute\Attribute';
        $attribute = Mockery::mock($attribute);

        $option = new AttributeOption;
        $option->setAttribute($attribute);
        $option->setName('An option');
        $option->setVariable('anOption');
        $option->setType('string');

        $validator = new AttributeOptionValidator($this->getLocator());
        $result = $validator->validate($option);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we fail validation for invalid options.
     * @test
     */
    public function canValidateOptionAndFail()
    {
        $validator = new AttributeOptionValidator($this->getLocator());
        $option = new AttributeOption;

        $result = $validator->validate($option);
        $errors = $result->getErrors();

        $this->assertFalse($result->isValid());
        $this->assertTrue(isset($errors['name']));
        $this->assertTrue(isset($errors['variable']));
        $this->assertTrue(isset($errors['type']));
        $this->assertTrue(isset($errors['attribute']));
    }





}//class ends here