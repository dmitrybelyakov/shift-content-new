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
namespace ShiftContentNew\Type\Field;

use Zend\Di\Locator;
use ShiftContent\Module;
use ShiftContentNew\Exception\ConfigurationException;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\FieldType\FieldTypeFactory;


/**
 * Field factory
 * Responsible for creation of content type fields and managing their
 * filter and validator attributes.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldFactory
{

    /**
     * Service locator instance
     * @var \Zend\Di\Locator
     */
    protected $locator;

    /**
     * Content module configuration
     * @var array
     */
    protected $config = array();

    /**
     * Field type factory
     * @var \ShiftContentNew\FieldType\FieldTypeFactory
     */
    protected $fieldTypeFactory;


    /**
     * Construct
     * Instantiates field factory. Requires an instance of service locator.
     *
     * @param \Zend\Di\Locator $locator
     * @return void
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }


    /**
     * Set config
     * Allows you to inject arbitrary config to be used within
     *
     * @param array $config
     * @return \ShiftContentNew\Type\Field\FieldFactory
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }


    /**
     * Get config
     * Checks if we already have a config injected and returns that.
     * Otherwise obtains config from module bootstrap.
     *
     * @return array
     */
    public function getConfig()
    {
        if(empty($this->config))
            $this->config = Module::getModuleConfig()->toArray();
        return $this->config;
    }


    /**
     * Set field type factory
     * Allows you to inject arbitrary field type factory to be used.
     *
     * @param \ShiftContentNew\FieldType\FieldTypeFactory $fieldTypeFactory
     * @return \ShiftContentNew\Type\Field\FieldFactory
     */
    public function setFieldTypeFactory(FieldTypeFactory $fieldTypeFactory)
    {
        $this->fieldTypeFactory = $fieldTypeFactory;
        return $this;
    }

    /**
     * Get field type factory
     * Returns currently injected field type factory if it was injected or
     * obtains one from service locator.
     *
     * @return \ShiftContentNew\FieldType\FieldTypeFactory
     */
    public function getFieldTypeFactory()
    {
        if(!$this->fieldTypeFactory)
        {
            $this->fieldTypeFactory = $this->locator->get(
                'ShiftContentNew\FieldType\FieldTypeFactory'
            );
        }

        return $this->fieldTypeFactory;
    }


    /**
     * Get field types
     * Returns an array of configured field types.
     * @return array
     */
    public function getFieldTypes()
    {
        $config = $this->getConfig();
        $types = $config['contentFields'];
        return $types;
    }


    /**
     * Get field type
     * Returns field type object if it's configured
     * @param string $className
     * @return \ShiftContentNew\FieldType\AbstractFieldType | null
     */
    public function getFieldType($className)
    {
        $types = $this->getFieldTypes();
        foreach($types as $type)
        {
            if($type == $className)
                return new $className;
        }
    }


    /**
     * Create field
     * Allows to assemble a field by it's type short name. May optionally
     * accept an array data set to populate field upon instantiation.
     *
     * @param string $shortName
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Field | null
     */
    public function createField($shortName, array $data = array())
    {
        $types = $this->getFieldTypes();
        if(!array_key_exists($shortName, $types))
            return;

        return $this->createFieldOfType($types[$shortName], $data);
    }


    /**
     * Create field
     * Creates and configured field by its field type class name. May optionally
     * accept an array data set to populate field upon instantiation.
     *
     * @param string $typeClassName
     * @param array $data
     * @throws \ShiftContentNew\Exception\ConfigurationException
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function createFieldOfType($typeClassName, array $data = array())
    {
        $type = $this->getFieldType($typeClassName);

        if(!$type)
        {
            $error = "Field '$typeClassName' is not configured";
            throw new ConfigurationException($error);
        }

        $field = new Field($data);
        $field->setFieldType($typeClassName);

        $settings = $this->getFieldTypeFactory()->getSettings($type);
        if($settings)
            $field->setSettings($settings);

        return $field;

    }



} //class ends here