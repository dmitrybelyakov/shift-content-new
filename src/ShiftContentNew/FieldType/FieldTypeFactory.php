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
 * @subpackage  Field
 */

/**
 * @namespace
 */
namespace ShiftContentNew\FieldType;

use Zend\Di\Locator;
use ShiftContentNew\Module;
use ShiftContentNew\FieldType\AbstractFieldType;
use ShiftContentNew\Exception\ConfigurationException;

/**
 * Field type factory
 * Used to validate and create field types and their supporting
 * classes.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Field
 */
class FieldTypeFactory
{

    /**
     * Service locator instance
     * @var \Zend\Di\Locator
     */
    protected $locator;

    /**
     * Module configuration
     * @var array
     */
    protected $config;


    /**
     * Construct
     * Instantiates field type factory. Requires an instance of service locator.
     * @return void
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }


    /**
     * Set config
     * Allows to inject arbitrary config.
     *
     * @param array $config
     * @return \ShiftContentNew\FieldType\FieldTypeFactory
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }


    /**
     * Get config
     * Check to see if we already have a config injected and returns that.
     * Otherwise retrieves config from module bootstrap.
     *
     * @return array
     */
    public function getConfig()
    {
        if(!$this->config)
            $this->config = Module::getModuleConfig()->toArray();
        return $this->config;
    }


    /**
     * Get field types
     * Returns an array of registered field types.
     * @return array
     */
    public function getFieldTypes()
    {
        $config = $this->getConfig();

        $fieldTypes = array();
        foreach($config['contentFields'] as $shortName => $className)
            $fieldTypes[$shortName] = new $className;

        return $fieldTypes;
    }

    /**
     * Get value
     * Instantiates and returns value entity with configured class name.
     *
     * @param \ShiftContentNew\FieldType\AbstractFieldType $fieldType
     * @throw \ShiftContent\Exception\ConfigurationException
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue
     */
    public function getValue(AbstractFieldType $fieldType)
    {
        $type = 'ShiftContentNew\FieldValue\AbstractFieldValue';

        $class = $fieldType->getValueClass();
        $value = new $class;

        if(!$value instanceof $type)
        {
            $error = "Field value must be of $type type";
            throw new ConfigurationException($error);
        }

        //return on success
        return $value;
    }


    /**
     * Get settings
     * Instantiates and returns settings entity with configured class name.
     *
     * @param \ShiftContentNew\FieldType\AbstractFieldType $fieldType
     * @throw \ShiftContent\Exception\ConfigurationException
     * @return \ShiftContentNew\FieldType\AbstractSettings  | void
     */
    public function getSettings(AbstractFieldType $fieldType)
    {
        $type = 'ShiftContentNew\FieldType\AbstractSettings';

        $class = $fieldType->getSettingsClass();
        if(!$class)
            return;

        $settings = new $class;

        if(!$settings instanceof $type)
        {
            $error = "Field settings must be of $type type";
            throw new ConfigurationException($error);
        }

        //return on success
        return $settings;
    }


    /**
     * Get settings validator
     * Instantiates and returns settings entity validator with
     * configured class name.
     *
     * @param \ShiftContentNew\FieldType\AbstractFieldType $fieldType
     * @throw \ShiftContent\Exception\ConfigurationException
     * @return \ShiftCommon\Model\Entity\EntityValidator  | void
     */
    public function getSettingsValidator(AbstractFieldType $fieldType)
    {
        $type = 'ShiftCommon\Model\Entity\EntityValidator';

        $class = $fieldType->getSettingsValidatorClass();
        if(!$class)
            return;

        $class = ltrim($class, '\\'); //important to get from locator
        $settingsValidator = $this->locator->newInstance($class);

        if(!$settingsValidator instanceof $type)
        {
            $error = "Field settings validator must be of $type type";
            throw new ConfigurationException($error);
        }

        //return on success
        return $settingsValidator;
    }


    /**
     * Get editor
     * Instantiates and returns editor form element with configured class name.
     *
     * @param \ShiftContentNew\FieldType\AbstractFieldType $fieldType
     * @throw \ShiftContent\Exception\ConfigurationException
     * @return \Zend\Form\Element
     */
    public function getEditor(AbstractFieldType $fieldType)
    {
        $type = 'Zend\Form\Element';

        $class = $fieldType->getEditorClass();
        $editor = new $class('__noname');

        if(!$editor instanceof $type)
        {
            $error = "Field editor must be of $type type";
            throw new ConfigurationException($error);
        }

        //return on success
        return $editor;
    }


    /**
     * Get value processor
     * Instantiates and returns value processor with configured class name.
     *
     * @param \ShiftContentNew\FieldType\AbstractFieldType $fieldType
     * @throw \ShiftContent\Exception\ConfigurationException
     * @return \ShiftContentNew\FieldType\AbstractValueProcessor | void
     */
    public function getValueProcessor(AbstractFieldType $fieldType)
    {
        $type = 'ShiftContentNew\FieldType\AbstractValueProcessor';

        $class = $fieldType->getValueProcessorClass();
        $valueProcessor = new $class;

        if(!$valueProcessor instanceof $type)
        {
            $error = "Field value processor must be of $type type";
            throw new ConfigurationException($error);
        }

        //return on success
        return $valueProcessor;
    }



} //class ends here