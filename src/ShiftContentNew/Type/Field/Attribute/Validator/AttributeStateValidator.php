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
 * @subpackage  Type
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Type\Field\Attribute\Validator;
use Zend\Validator\AbstractValidator;

use Zend\Di\Di as Locator;

/**
 * Attribute state validator
 * This validator checks that field attribute is properly assembled
 * according to configured attribute types.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeStateValidator extends AbstractValidator
{

    /**
     * Service locator instance
     * @var \Zend\Di\Di
     */
    protected $locator;


    /**
     * Error message container
     * @var string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const ATTRIBUTE_MISCONFIGURED = 'attributeMisconfigured';
    const OPTIONS_MISCONFIGURED = 'optionsMisconfigured';
    const NO_CONFIG_FOR_CLASS = 'noConfigForClass';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::NO_CONFIG_FOR_CLASS =>
            "Unable to find configuration for attribute class",
        self::ATTRIBUTE_MISCONFIGURED =>
            "Attribute misconfigured. Please create attributes with factory.",
        self::OPTIONS_MISCONFIGURED =>
            "Attribute options misconfigured. Create attributes with factory.",
    );


    /**
     * Is valid
     * Validates field attribute type to return a boolean result.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $attribute
     * @return bool
     */
    public function isValid($attribute)
    {
        //set value inside validator
        $this->setValue($attribute);

        //get class
        $className = $attribute->getClassName();
        if(!$className)
        {
            $this->error(self::ATTRIBUTE_MISCONFIGURED);
            return false;
        }


        //get config from factory
        $factory = 'ShiftContentNew\Type\Field\Attribute\AttributeFactory';
        $factory = $this->getLocator()->get($factory);
        $config = $factory->getTypeByClassName($className);
        if(empty($config))
        {
            $this->error(self::NO_CONFIG_FOR_CLASS);
            return false;
        }

        //validate options count
        if(count($config['options']) != count($attribute->getOptions()))
        {
            $this->error(self::OPTIONS_MISCONFIGURED);
            return false;
        }

        //validate each option
        $expected = $config['options'];
        foreach($expected as $variable => $settings)
        {
            $option = $attribute->getOptionByVariableName($variable);

            if(!$option || $settings['name'] != $option->getName() ||
                $settings['type'] != $option->getType())
            {
                $this->error(self::OPTIONS_MISCONFIGURED);
                return false;
            }
        }

        //return success if everything is valid
        return true;
    }


    /**
     * Set locator
     * Allows you to inject arbitrary locator.
     *
     * @param \Zend\Di\Di $locator
     * @return
     *  \ShiftContentNew\Type\Field\Attribute\Validator\AttributeStateValidator
     */
    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
        return $this;
    }


    /**
     * Get locator
     * Returns service locator instance.
     * @return \Zend\Di\Di
     */
    public function getLocator()
    {
        return $this->locator;
    }



}//class ends here