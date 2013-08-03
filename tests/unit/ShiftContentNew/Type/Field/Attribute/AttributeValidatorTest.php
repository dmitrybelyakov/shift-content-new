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

use ShiftContentNew\Type\Field\Attribute\AttributeValidator;
use ShiftContentNew\Type\Field\Attribute\AttributeFactory;
use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Type\Field\Attribute\AttributeOption;


/**
 * Attribute validator test
 * Tests attribute validator functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeValidatorTest extends TestCase
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
        $validator = new AttributeValidator($this->getLocator());
        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\AttributeValidator',
            $validator
        );
    }


    /**
     * Test that we can validate attribute aggregate and pass validation.
     * @test
     */
    public function canValidateAndPass()
    {
        //create attribute with factory
        $factory = new AttributeFactory;
        $config = array_shift($factory->getValidatorTypes());
        $class = $config['class'];
        $attribute = $factory->createValidatorByClassName($class);

        //set parent field
        $field = Mockery::mock('ShiftContentNew\Type\Field\Field');
        $attribute->setField($field);

        $validator = new AttributeValidator($this->getLocator());
        $result = $validator->validate($attribute);
        $this->assertTrue($result->isValid());
    }


    /**
     * Test that we fai validation if attribute aggregate adn fail if it is
     * misconfigured.
     * @test
     */
    public function canValidateAndFail()
    {
        $factory = new AttributeFactory;
        $config = array_shift($factory->getValidatorTypes());

        //here we create attribute with correct options (to pass state check)
        $attribute = new Attribute;
        $attribute->setClassName($config['class']);
        foreach($config['options'] as $variable => $settings)
        {
            $option = new AttributeOption(array(
                'variable' => $variable,
                'name' => $settings['name'],
                'type' => $settings['type'],
            ));
            $attribute->addOption($option);
        }

        $validator = new AttributeValidator($this->getLocator());
        $result = $validator->validate($attribute);
        $errors = $result->getErrors();

        $this->assertFalse($result->isValid());
        $this->assertTrue(isset($errors['type']));
        $this->assertTrue(isset($errors['field']));
    }


    /**
     * Test that we can validate attribute stat and fail if it is
     * misconfigured (entity state validation fails)
     * @test
     */
    public function canValidateAndFailStateCheck()
    {
        $factory = new AttributeFactory;
        $config = array_shift($factory->getValidatorTypes());

        $attribute = new Attribute;
        $attribute->setClassName($config['class']);
        $attribute->setType('validator');
        $attribute->setField(Mockery::mock('ShiftContentNew\Type\Field\Field'));

        $validator = new AttributeValidator($this->getLocator());
        $result = $validator->validate($attribute);
        $errors = $result->getErrors();

        $this->assertFalse($result->isValid());
        $this->assertTrue(isset(
            $errors['stateErrors']['attributeConfig']['optionsMisconfigured']
        ));
    }





}//class ends here