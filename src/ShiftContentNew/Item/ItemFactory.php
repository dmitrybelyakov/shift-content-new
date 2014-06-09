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
 * @subpackage  Item
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Item;

use Zend\ServiceManager\ServiceManager;
use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\TypeService;
use ShiftContentNew\FieldType\FieldTypeFactory;
use ShiftContentNew\Exception\DomainException;
use ShiftContentNew\Exception\ConfigurationException;

/**
 * Item factory
 * Is used to assemble content items from the given content types, as well as
 * assemble item validators.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class ItemFactory
{
    /**
     * Service manager instance
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * Content type service instance
     * @var \ShiftContentNew\Type\TypeService
     */
    protected $typeService;

    /**
     * Field type factory
     * @var \ShiftContentNew\FieldType\FieldTypeFactory
     */
    protected $fieldTypeFactory;


    /**
     * Construct
     * Instantiates factory service. Requires an instance of service manager.
     *
     * @param \Zend\ServiceManager\ServiceManager $sm
     * @return void
     */
    public function __construct(ServiceManager $sm)
    {
        $this->sm = $sm;
    }


    /**
     * Set type service
     * Allows you to inject arbitrary type service.
     *
     * @param \ShiftContentNew\Type\TypeService $typeService
     * @return \ShiftContentNew\Item\ItemFactory
     */
    public function setTypeService(TypeService $typeService)
    {
        $this->typeService = $typeService;
        return $this;
    }

    /**
     * Get type service
     * Checks if we already have a type service injected and returns that.
     * Otherwise obtains an instance from service manager.
     *
     * @return null|\ShiftContentNew\Type\TypeService
     */
    public function getTypeService()
    {
        if(!$this->typeService)
        {
            $this->typeService = $this->sm->get(
                'ShiftContentNew\Type\TypeService'
            );
        }

        return $this->typeService;
    }


    /**
     * Set field type factory
     * Allows you to inject arbitrary field type factory into service.
     *
     * @param \ShiftContentNew\FieldType\FieldTypeFactory $fieldTypeFactory
     * @return \ShiftContentNew\Item\ItemFactory
     */
    public function setFieldTypeFactory(FieldTypeFactory $fieldTypeFactory)
    {
        $this->fieldTypeFactory = $fieldTypeFactory;
        return $this;
    }

    /**
     * Get field type factory
     * Checks if we already have field type factory injected and returns that.
     * Otherwise obtains an instance from service manager.
     *
     * @return null|\ShiftContentNew\Type\TypeService
     */
    public function getFieldTypeFactory()
    {
        if(!$this->fieldTypeFactory)
        {
            $this->fieldTypeFactory = $this->sm->get(
                'ShiftContentNew\FieldType\FieldTypeFactory'
            );
        }

        return $this->fieldTypeFactory;
    }


    /**
     * Create item of type
     * A factory method to create a new content item of a given content type
     * that can be provided as persisted type id. May optionally populate
     * item at instantiation.
     *
     * @param \ShiftContentNew\Type\Type | int $type
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Item\Item
     */
    public function createItemOfType($type, array $data = array())
    {
        //get type by id
        if(is_numeric($type))
            $type = $this->getTypeService()->getType($type);

        //check type
        if(!$type instanceof Type)
            throw new DomainException('Unable to locate content type');

        //now create item
        $item = new Item;
        $item->setContentType($type);

        //and add properties to it
        $fieldTypes = array();
        foreach($type->getFields() as $field)
        {
            //create field type object and preserve it (for performance)
            $fieldTypeName = $field->getFieldType();
            if(!isset($fieldTypes[$fieldTypeName]))
                $fieldTypes[$fieldTypeName] = new $fieldTypeName;

            //create item property
            $fieldType = $fieldTypes[$fieldTypeName];
            $property = $this->getFieldTypeFactory()->getValue($fieldType);
            $property->setName($field->getProperty());
            $item->addProperty($property);
        }

        //populate from array
        if(!empty($data))
            $item->fromArray($data);

        return $item;
    }


    /**
     * Create validator for item
     * Creates validator for the given content item by accessing its content
     * type. Will throw an exception if type is not set.
     *
     * @param \ShiftContentNew\Item\Item $item
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftCommon\Model\Entity\EntityValidator
     */
    public function createValidatorForItem(Item $item)
    {
        $contentType = $item->getContentType();
        if(!$contentType)
            throw new DomainException('This content item has no type');

        //otherwise create validator
        return $this->createValidatorOfType($contentType);
    }


    /**
     * Create validator of type
     * A factory method to create validator for content items of a given
     * content type that can be provided as persisted type id.
     *
     * @param \ShiftContentNew\Type\Type | int $type
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftCommon\Model\Entity\EntityValidator
     */
    public function createValidatorOfType($type)
    {
        //get type by id
        if(is_numeric($type))
            $type = $this->getTypeService()->getType($type);

        //check type
        if(!$type instanceof Type)
            throw new DomainException('Unable to locate content type');

        //create base validator
        $itemValidator = 'ShiftContentNew\Item\ItemValidator';
        $itemValidator = $this->sm->get($itemValidator);

        //now add validators for custom properties
        foreach($type->getFields() as $field)
        {
            //add property
            $property = $field->getProperty();
            $itemValidator->addProperty($property);

            //add custom filters
            $filters = $field->getFilters();
            foreach($filters as $config)
            {
                $className = $config->getClassName();
                if($this->sm->has($className))
                    $filter = $this->sm->get($className);
                else
                {
                    try
                    {
                        $filter = new $className;
                    }
                    catch(\Exception $e)
                    {
                        $error = "Service manager was unable to create ";
                        $error .= "filter '$className'. Direct ";
                        $error .= "instantiation failed as well.";
                        throw new ConfigurationException($error);
                    }
                }

                $filterOptions = $config->getOptionValues();
                foreach($filterOptions as $variable => $value)
                {
                    if($value !== null)
                    {
                        $method = 'set' . ucfirst($variable);
                        if(method_exists($filter, $method))
                            $filter->$method($value);
                    }
                }

                $itemValidator->$property->addFilter($filter);
                unset($filter, $filterOptions, $variable, $value);//!!
            }

            //add custom validators
            $validators = $field->getValidators();
            foreach($validators as $config)
            {
                $className = $config->getClassName();
                if($this->sm->has($className))
                    $validator = $this->sm->get($className);
                else
                {
                    try
                    {
                        $validator = new $className;
                    }
                    catch(\Exception $e)
                    {
                        $error = "Service manager was unable to create ";
                        $error .= "validator '$className'. Direct ";
                        $error .= "instantiation failed as well.";
                        throw new ConfigurationException($error);
                    }
                }

                $validatorOptions = $config->getOptionValues();
                foreach($validatorOptions as $variable => $value)
                {
                    if($value !== null)
                    {
                        $method = 'set' . ucfirst($variable);
                        if(method_exists($validator, $method))
                            $validator->$method($value);
                    }
                }

                $itemValidator->$property->addValidator($validator);
                unset($validator, $validatorOptions, $variable, $value);//!!
            }
        }

        return $itemValidator;
    }



} //class ends here