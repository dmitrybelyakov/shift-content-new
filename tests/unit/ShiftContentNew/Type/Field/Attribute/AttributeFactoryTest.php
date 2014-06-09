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

use ShiftContentNew\Type\Field\Attribute\AttributeFactory;

/**
 * Attribute factory test
 * This holds unit tests for attribute factory that creates field attributes
 * based on their configured definitions
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Tests
 *
 * @group       unit
 */
class AttributeFactoryTest extends TestCase
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
     * Test that we are able to instantiate factory.
     * @test
     */
    public function canInstantiateFactory()
    {
        $factoryName = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $factory = $this->sm()->get($factoryName);
        $this->assertInstanceOf($factoryName, $factory);
    }


    /**
     * Test that we can inject arbitrary configuration into factory.
     * @test
     */
    public function canInjectArbitraryConfig()
    {
        $config = array('me-is-config');
        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $this->assertEquals($config, $factory->getConfig());
    }


    /**
     * Test that we use default configuration from module bootstrap if none
     * is explicitly injected.
     * @test
     */
    public function useDefaultConfigFromBootstrapIfNoneInjected()
    {
        $factory = new AttributeFactory;
        $config = $factory->getConfig();

        $this->assertTrue(is_array($config));
        $this->assertFalse(empty($config));
    }


    /**
     * Test that we are able to get a list of filter types from config.
     * @test
     */
    public function canGetFilterTypes()
    {
        $factory = new AttributeFactory;
        $filters = $factory->getFilterTypes();

        $this->assertTrue(is_array($filters));
        $this->assertTrue(isset($filters['Alnum']));
    }


    /**
     * Test that we are able to get a list of validator types from config.
     * @test
     */
    public function canGetValidatorTypes()
    {
        $factory = new AttributeFactory;
        $validators = $factory->getFilterTypes();

        $this->assertTrue(is_array($validators));
        $this->assertTrue(isset($validators['Alnum']));
    }


    /**
     * Test that we are able to get attribute type by class name.
     * @test
     */
    public function canGetAttributeTypeByClassName()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'validators' => array(
                    'Alpha' => array(
                        'name' => 'Alphabetic',
                        'description' => 'Checks that input contains alphas',
                        'class' => 'Zend\Validator\Alpha',
                        'options' => array(
                            'allowWhitespace' => array(
                                'name' => 'Allow whitespaces',
                                'type' => 'bool'
                            )
                        )
                    ),
                ),
                'filters' => array(
                    'StringTrim' => array(
                        'name' => 'Trim',
                        'description' => 'Trims whitespace',
                        'class' => 'Zend\Filter\StringTrim',
                        'options' => array(
                            'charList' => array(
                                'name' => 'Character list',
                                'type' => 'string'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $data = $config['fieldAttributes'];

        $this->assertNull(
            $factory->getTypeByClassName('No\Such\Class')
        );

        $this->assertEquals(
            $data['validators']['Alpha'],
            $factory->getTypeByClassName('Zend\Validator\Alpha')
        );

        $this->assertEquals(
            $data['filters']['StringTrim'],
            $factory->getTypeByClassName('Zend\Filter\StringTrim')
        );

    }


    /**
     * Test that we throw proper exception if requested filter is not
     * configured when creating filter.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Filter 'No\Such\Class' is not configured
     */
    public function throwExceptionIfRequestedFilterNotConfigured()
    {
        $factory = new AttributeFactory;
        $factory->createFilterByClassName('No\Such\Class');
    }


    /**
     * Test that we are able to create a filter by class name.
     * @test
     */
    public function canCreateFilterByClassName()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'filters' => array(
                    'StringTrim' => array(
                        'name' => 'Trim',
                        'description' => 'Trims whitespace',
                        'class' => 'Zend\Filter\StringTrim',
                        'options' => array(
                            'charList' => array(
                                'name' => 'Character list',
                                'type' => 'string'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $filter = $factory->createFilterByClassName('Zend\Filter\StringTrim');

        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\Attribute',
            $filter
        );

        $data = $config['fieldAttributes']['filters']['StringTrim'];
        $this->assertEquals('filter', $filter->getType());
        $this->assertEquals($data['class'], $filter->getClassName());
        $this->assertNull($filter->getCharList()); //no error = exists
    }


    /**
     * Test that we are able to create filter by short name.
     * @test
     */
    public function canCreateFilter()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'filters' => array(
                    'StringTrim' => array(
                        'name' => 'Trim',
                        'description' => 'Trims whitespace',
                        'class' => 'Zend\Filter\StringTrim',
                        'options' => array(
                            'charList' => array(
                                'name' => 'Character list',
                                'type' => 'string'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $filter = $factory->createFilter('StringTrim');

        $data = $config['fieldAttributes']['filters']['StringTrim'];
        $this->assertEquals('filter', $filter->getType());
        $this->assertEquals($data['class'], $filter->getClassName());
        $this->assertNull($filter->getCharList()); //no error = exists

        //assert we get nul for not configured short name
        $this->assertNull($factory->createFilter('NonExistent'));
    }


    /**
     * Test that we are able to create filter and set its options at once.
     * @test
     */
    public function canCreateFilterAndSetOptions()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'filters' => array(
                    'StringTrim' => array(
                        'name' => 'Trim',
                        'description' => 'Trims whitespace',
                        'class' => 'Zend\Filter\StringTrim',
                        'options' => array(
                            'charList' => array(
                                'name' => 'Character list',
                                'type' => 'string'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);

        $data = array('charList' => 'a,b,c');
        $filter = $factory->createFilter('StringTrim', $data);

        $data = $config['fieldAttributes']['filters']['StringTrim'];
        $this->assertEquals('filter', $filter->getType());
        $this->assertEquals($data['class'], $filter->getClassName());
        $this->assertEquals('a,b,c', $filter->getCharList());
    }


    /**
     * Test that we throw proper exception if requested validator is not
     * configured when creating validator.
     *
     * @test
     * @expectedException \ShiftContentNew\Exception\ConfigurationException
     * @expectedExceptionMessage Validator 'No\Such\Class' is not configured
     */
    public function throwExceptionIfRequestedValidatorNotConfigured()
    {
        $factory = new AttributeFactory;
        $factory->createValidatorByClassName('No\Such\Class');
    }


    /**
     * Test that we are able to create a validator by class name.
     * @test
     */
    public function canCreateValidatorByClassName()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'validators' => array(
                    'Alpha' => array(
                        'name' => 'Alphabetic',
                        'description' => 'Checks that input contains alphas',
                        'class' => 'Zend\I18n\Validator\Alpha',
                        'options' => array(
                            'allowWhitespace' => array(
                                'name' => 'Allow whitespaces',
                                'type' => 'bool'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $validator = $factory->createValidatorByClassName(
            'Zend\I18n\Validator\Alpha'
        );

        $this->assertInstanceOf(
            'ShiftContentNew\Type\Field\Attribute\Attribute',
            $validator
        );

        $data = $config['fieldAttributes']['validators']['Alpha'];
        $this->assertEquals('validator', $validator->getType());
        $this->assertEquals($data['class'], $validator->getClassName());
        $this->assertNull($validator->getAllowWhitespace());
    }


    /**
     * Test that we are able to create validator by short name.
     * @test
     */
    public function canCreateValidator()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'validators' => array(
                    'Alpha' => array(
                        'name' => 'Alphabetic',
                        'description' => 'Checks that input contains alphas',
                        'class' => 'Zend\I18n\Validator\Alpha',
                        'options' => array(
                            'allowWhitespace' => array(
                                'name' => 'Allow whitespaces',
                                'type' => 'bool'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);
        $validator = $factory->createValidator('Alpha');

        $data = $config['fieldAttributes']['validators']['Alpha'];
        $this->assertEquals('validator', $validator->getType());
        $this->assertEquals($data['class'], $validator->getClassName());
        $this->assertNull($validator->getAllowWhitespace()); //no error = exists

        //assert we get nul for not configured short name
        $this->assertNull($factory->createValidator('NonExistent'));
    }


    /**
     * Test that we are able to create validator and set its options at once.
     * @test
     */
    public function canCreateValidatorAndSetOptionsAtOnce()
    {
        //prepare config
        $config = array(
            'fieldAttributes' => array(
                'validators' => array(
                    'Alpha' => array(
                        'name' => 'Alphabetic',
                        'description' => 'Checks that input contains alphas',
                        'class' => 'Zend\I18n\Validator\Alpha',
                        'options' => array(
                            'allowWhitespace' => array(
                                'name' => 'Allow whitespaces',
                                'type' => 'bool'
                            )
                        )
                    ),
                )
            )
        );

        $factory = new AttributeFactory;
        $factory->setConfig($config);

        $data = array('allowWhitespace' => true);
        $validator = $factory->createValidator('Alpha', $data);

        $data = $config['fieldAttributes']['validators']['Alpha'];
        $this->assertEquals('validator', $validator->getType());
        $this->assertEquals($data['class'], $validator->getClassName());
        $this->assertTrue($validator->getAllowWhitespace());
    }



}//class ends here