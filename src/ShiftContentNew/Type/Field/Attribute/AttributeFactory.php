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
namespace ShiftContentNew\Type\Field\Attribute;

use ShiftContentNew\Module;
use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Type\Field\Attribute\AttributeOption as Option;
use ShiftContentNew\Exception\ConfigurationException;

/**
 * Attribute factory
 * Responsible for creation of field attributes based on their configured
 * definitions.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeFactory
{

    /**
     * Content module configuration
     * @var array
     */
    protected $config;


    /**
     * Set config
     * Allows to inject arbitrary configuration to be used within factory.
     *
     * @param array $config
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeFactory
     */
    public function setConfig(array $config = array())
    {
        $this->config = $config;
        return $this;
    }


    /**
     * Get config
     * Checks if there's a config set and obtains one from module bootstrap
     * if not.
     * @return array
     */
    public function getConfig()
    {
        if(empty($this->config))
            $this->config = Module::getModuleConfig()->toArray();
        return $this->config;
    }


    /**
     * Get filters
     * Returns an array of configured field filters.
     * @return array
     */
    public function getFilterTypes()
    {
        $configKey = 'fieldAttributes';
        $config = $this->getConfig();
        $filters = $config[$configKey]['filters'];
        return $filters;
    }

    /**
     * Get type by class name
     * Returns attribute type by its class name.
     *
     * @param string $className
     * @return array | void
     */
    public function getTypeByClassName($className)
    {
        $filters = $this->getFilterTypes();
        $validators = $this->getValidatorTypes();

        $types = array();
        foreach($filters as $filter)
            $types[$filter['class']] = $filter;

        foreach($validators as $validator)
            $types[$validator['class']] = $validator;

        if(!array_key_exists($className, $types))
            return null;

        return $types[$className];
    }


    /**
     * Create filter by class name
     * Creates a filter attribute from configured definition by class name.
     * May optionally set option values from an array data set.
     *
     * @param string $className
     * @param array $data
     * @throw \ShiftContentNew\Exception\ConfigurationException
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function createFilterByClassName($className, array $data = array())
    {
        $filters = $this->getFilterTypes();
        foreach($filters as $filter)
        {
            if($filter['class'] == $className)
            {
                $type = $filter;
                break;
            }

        }

        if(!isset($type))
        {
            $error = "Filter '$className' is not configured";
            throw new ConfigurationException($error);
        }

        $filter = new Attribute(array(
            'type' => 'filter',
            'className' => $type['class'],
        ));

        foreach($type['options'] as $variable => $variableSettings)
        {
            $option = new Option(array(
                'variable' => $variable,
                'type' => $variableSettings['type'],
                'name' => $variableSettings['name']
            ));

            if(isset($data[$variable]))
                $option->setValue($data[$variable]);

            $filter->addOption($option);
        }

        return $filter;
    }


    /**
     * Create filter
     * Creates a filter by its configured short name.
     * May optionally set option values from an array data set.
     *
     * @param string $name
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute | void
     */
    public function createFilter($name, array $data = array())
    {
        $filters = $this->getFilterTypes();
        if(isset($filters[$name]))
        {
            return $this->createFilterByClassName(
                $filters[$name]['class'],
                $data
            );
        }

    }


    /**
     * Create validator by class name
     * Creates a validator attribute from configured definition by class name.
     * May optionally set option values from an array data set.
     *
     * @param string $className
     * @param array $data
     * @throw \ShiftContentNew\Exception\ConfigurationException
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function createValidatorByClassName(
        $className,
        array $data = array())
    {
        $validators = $this->getValidatorTypes();
        foreach($validators as $validator)
        {
            if($validator['class'] == $className)
            {
                $type = $validator;
                break;
            }

        }

        if(!isset($type))
        {
            $error = "Validator '$className' is not configured";
            throw new ConfigurationException($error);
        }

        $validator = new Attribute(array(
            'type' => 'validator',
            'className' => $type['class'],
        ));

        foreach($type['options'] as $variable => $variableSettings)
        {
            $option = new Option(array(
                'variable' => $variable,
                'type' => $variableSettings['type'],
                'name' => $variableSettings['name']
            ));

            if(isset($data[$variable]))
                $option->setValue($data[$variable]);

            $validator->addOption($option);
        }

        return $validator;
    }

    /**
     * Create validator
     * Creates a validator by its configured short name.
     * May optionally set option values from an array data set.
     *
     * @param string $name
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute | void
     */
    public function createValidator($name, array $data = array())
    {
        $validators = $this->getValidatorTypes();
        if(isset($validators[$name]))
        {
            return $this->createValidatorByClassName(
                $validators[$name]['class'],
                $data
            );
        }
    }


    /**
     * Get validators
     * Returns an array of configured field validators.
     * @return array
     */
    public function getValidatorTypes()
    {
        $configKey = 'fieldAttributes';
        $config = $this->getConfig();
        $validators = $config[$configKey]['validators'];
        return $validators;
    }



} //class ends here