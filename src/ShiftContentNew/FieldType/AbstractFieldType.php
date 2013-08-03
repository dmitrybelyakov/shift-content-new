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

use ShiftContentNew\Exception\DomainException;
use ShiftContentNew\Exception\ConfigurationException;

/**
 * Field type
 * An accessor wrapper for field configuration options.
 * Provides object-oriented access to field configuration.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Field
 */
abstract class AbstractFieldType
{
    /**
     * Field name
     * @var string
     */
    protected $name;

    /**
     * Field description
     * @var string
     */
    protected $description;

    /**
     * Value class name
     * @var string
     */
    protected $valueClass;

    /**
     * Field settings class
     * @var string
     */
    protected $settingsClass;

    /**
     * Field settings validator class
     * @var string
     */
    protected $settingsValidatorClass;

    /**
     * Field editor class
     * @var string
     */
    protected $editorClass;

    /**
     * Field value processor class
     * @var string
     */
    protected $valueProcessorClass;



    /**
     * Construct
     * Instantiates field type.
     * @return void
     */
    public function __construct()
    {
        $this->init();
        $this->checkIntegrity();
    }


    /**
     * Init
     * Initializes field type. Implement this in your concrete field type
     * implementations.
     *
     * @return mixed
     */
    public abstract function init();


    /**
     * Check integrity
     * Checks that concrete field type implementation is properly configured.
     * Is automatically called by constructor at instantiation.
     *
     * @throws \ShiftContentNew\Exception\ConfigurationException
     * @return void
     */
    public function checkIntegrity()
    {
        $required = array(
            'name',
            'description',
            'valueClass',
            'editorClass'
        );

        foreach($required as $property)
        {
            if(!isset($this->$property))
            {
                $class = get_class($this);
                $error = "Field '$property' is not configured for $class";
                throw new ConfigurationException($error);
            }

            if($this->settingsClass && !$this->settingsValidatorClass)
            {
                $error = "Field '$property' must have settings validator";
                throw new ConfigurationException($error);
            }
        }

        return;
    }


    /**
     * From array
     * Populates field type from an array data set.
     *
     *
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data)
    {
        foreach($data as $property => $value)
        {
            $method = 'set' . ucfirst($property);
            if(method_exists($this, $method))
                $this->$method($value);
        }

        return $this;
    }


    /**
     * To array
     * Returns an array representation of field type.
     * @return array
     */
    public function toArray()
    {
        $type = array(
            'name' => $this->name,
            'description' => $this->description,
            'valueClass' => $this->valueClass,
            'settingsClass' => $this->settingsClass,
            'settingsValidatorClass' => $this->settingsValidatorClass,
            'editorClass' => $this->editorClass,
            'valueProcessorClass' => $this->valueProcessorClass,
        );
        return $type;
    }


    /**
     * Set name
     * Sets field type name.
     *
     * @param string $name
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }


    /**
     * Get name
     * Returns current field type name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set description
     * Sets field type description.
     *
     * @param string $description
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }


    /**
     * Get description
     * Returns current field type description.
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set value class
     * Sets field value class name.
     *
     * @param string $valueClass
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setValueClass($valueClass)
    {
        $valueClass = '\\' . ltrim($valueClass, '\\');

        if(!class_exists($valueClass))
        {
            $error = "Value class '$valueClass' does not exist";
            throw new DomainException($error);
        }


        $this->valueClass = (string) $valueClass;
        return $this;
    }


    /**
     * Get value class
     * Returns current field type value class name.
     * @return string
     */
    public function getValueClass()
    {
        return $this->valueClass;
    }


    /**
     * Set settings class
     * Sets field settings class name.
     *
     * @param string $settingsClass
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setSettingsClass($settingsClass = null)
    {
        if($settingsClass == null)
        {
            $this->settingsClass = null;
            return $this;
        }

        $settingsClass = '\\' . ltrim($settingsClass, '\\');

        if(!class_exists($settingsClass))
        {
            $error = "Settings class '$settingsClass' does not exist";
            throw new DomainException($error);
        }

        $this->settingsClass = (string) $settingsClass;
        return $this;
    }


    /**
     * Get settings class
     * Returns current field type settings class name.
     * @return string | void
     */
    public function getSettingsClass()
    {
        return $this->settingsClass;
    }


    /**
     * Set settings validator class
     * Sets field settings validator class name.
     *
     * @param string $settingsValidatorClass
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setSettingsValidatorClass($settingsValidatorClass = null)
    {
        if($settingsValidatorClass == null)
        {
            $this->settingsValidatorClass = null;
            return $this;
        }

        $validatorClass = '\\' . ltrim($settingsValidatorClass, '\\');

        if(!class_exists($validatorClass))
        {
            $error = "Settings validator class '$validatorClass' ";
            $error .= "does not exist";
            throw new DomainException($error);
        }

        $this->settingsValidatorClass = (string) $validatorClass;
        return $this;
    }


    /**
     * Get settings validator class
     * Returns current field type settings validator class name.
     * @return string | void
     */
    public function getSettingsValidatorClass()
    {
        return $this->settingsValidatorClass;
    }


    /**
     * Set editor class
     * Sets field editor class name.
     *
     * @param string $editorClass
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setEditorClass($editorClass)
    {
        $editorClass = '\\' . ltrim($editorClass, '\\');

        if(!class_exists($editorClass))
        {
            $error = "Editor class '$editorClass' does not exist";
            throw new DomainException($error);
        }

        $this->editorClass = (string) $editorClass;
        return $this;
    }


    /**
     * Get editor class
     * Returns current field editor class name.
     * @return string
     */
    public function getEditorClass()
    {
        return $this->editorClass;
    }


    /**
     * Set value processor class
     * Sets field value processor class name.
     *
     * @param string $valueProcessorClass
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\AbstractFieldType
     */
    public function setValueProcessorClass($valueProcessorClass = null)
    {
        $valueProcessorClass = '\\' . ltrim($valueProcessorClass, '\\');

        if(!class_exists($valueProcessorClass))
        {
            $error = "Value processor class '$valueProcessorClass'";
            $error .= " does not exist";
            throw new DomainException($error);
        }

        $this->valueProcessorClass = (string) $valueProcessorClass;
        return $this;
    }


    /**
     * Get value processor class
     * Returns current field value processor class name.
     * @return string | void
     */
    public function getValueProcessorClass()
    {
        return $this->valueProcessorClass;
    }

} //class ends here