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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Type\Field\Attribute\AttributeOption as Option;
use ShiftContentNew\Exception\DomainException;

/**
 * Content type field attribute
 * Encapsulates common functionality for content type filters and validators.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_type_field_attributes")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class Attribute
{
    /**
     * Attribute id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Attribute type
     * Can be either filter or validator
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $type;

    /**
     * Attribute class name
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $className;

    /**
     * Parent field
     * Maps attributes to field attributes collection
     *
     * @ORM\ManyToOne(
     *  targetEntity="ShiftContentNew\Type\Field\Field",
     *  inversedBy="attributes"
     * )
     * @ORM\JoinColumn(
     *  name="fieldId",
     *  referencedColumnName="id"
     * )
     * @var \ShiftContentNew\Type\Field\Field
     */
    protected $field;


    /**
     * Attribute options collection
     *
     * @ORM\OneToMany(
     *  targetEntity="\ShiftContentNew\Type\Field\Attribute\AttributeOption",
     *  mappedBy="attribute",
     *  orphanRemoval=true,
     *  cascade={"persist", "remove"})
     * )
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $options;


    /**
     * Construct
     * Instantiates content type field attribute.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->options = new ArrayCollection;
        $this->fromArray($data);
    }


    /**
     * From array
     * Populates attribute from array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function fromArray(array $data = array())
    {
        foreach($data as $property => $value)
        {
            $setter = 'set' . ucfirst($property);
            if(method_exists($this, $setter))
                $this->$setter($value);
        }

        return $this;
    }


    /**
     * To array
     * Returns an array representation of attribute.
     *
     * @return array
     */
    public function toArray()
    {
        $attribute = array(
            'id' => $this->id,
            'type' => $this->type,
            'className' => $this->className,
            'options' => array(),
        );

        if($this->options)
        {
            foreach($this->options as $option)
                $attribute['options'][] = $option->toArray();
        }

        return $attribute;
    }


    /**
     * Get id
     * Returns field attribute id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set type
     * Sets attribute type - either filter or validator.
     *
     * @param string $type
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function setType($type = null)
    {
        if($type == null)
        {
            $this->type = null;
            return;
        }

        //check type
        if($type != 'filter' && $type != 'validator')
        {
            $error = 'Attribute must be either filter or validator';
            throw new DomainException($error);
        }

        $this->type = $type;
        return $this;
    }


    /**
     * Get type
     * Returns attribute type.
     * @return string | void
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set class name
     * Sets attribute class name to be later use at instantiation.
     *
     * @param string $className
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function setClassName($className = null)
    {
        if($className == null)
        {
            $this->className = null;
            return;
        }

        if(!class_exists($className))
            throw new DomainException("No such class '$className'");

        $this->className = $className;
        return $this;
    }


    /**
     * Get class name
     * Returns attribute class name.
     * @return string | void
     */
    public function getClassName()
    {
        return $this->className;
    }


    /**
     * Set field
     * Sets parent field for attribute.
     *
     * @param \ShiftContentNew\Type\Field\Field $field
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function setField(Field $field)
    {
        $this->field = $field;
        return $this;
    }


    /**
     * Get field
     * Returns currently set field.
     *
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function getField()
    {
        return $this->field;
    }


    /**
     * Add option
     * Adds an attribute option to options collection.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\AttributeOption $option
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function addOption(Option $option)
    {
        //check option
        if(!$option->getName())
            throw new DomainException('Option must have a name');
        if(!$option->getVariable())
            throw new DomainException('Option must have a variable name');
        if(!$option->getType())
            throw new DomainException('Option must have a type');

        $option->setAttribute($this);
        $this->options->add($option);
        return $this;
    }


    /**
     * Remove option
     * Removes an attribute option from options collection.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\AttributeOption $option
     * @return \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    public function removeOption(Option $option)
    {
        $this->options->removeElement($option);
        return $this;
    }


    /**
     * Get options
     * Returns an array of attribute option objects.
     * @return array
     */
    public function getOptions()
    {
        return $this->options->toArray();
    }


    /**
     * Get option values
     * Returns a flat array of options and their values.
     * @return array
     */
    public function getOptionValues()
    {
        $values = array();
        foreach($this->options as $option)
            $values[$option->getVariable()] = $option->getValue();

        return $values;
    }


    /**
     * Get option by variable name
     * Returns attribute option object by its variable name.
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption | void
     */
    public function getOptionByVariableName($name)
    {
        foreach($this->options as $option)
        {
            if($name == $option->getVariable())
                return $option;
        }
    }


    // ------------------------------------------------------------------------

    /*
     * Magic access to options
     */


    /**
     * Call
     * Magic overloading method to call getters and setters on options.
     * Will scan for requested option variable and get/set if found.
     *
     * @param string $method
     * @param array $value
     * @throw \ShiftContentNew\Exception\DomainException
     * @return mixed
     */
    public function __call($method, array $value = null)
    {
        //get value
        if(!empty($value))
            $value = $value[0];

        //undefined method or property error
        $undefinedError = function($method) {
            $class = get_called_class();
            $trace = debug_backtrace(false);
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
            $error  = "Overloading error: Call to undefined method ";
            $error .= "$class::$method() in $file on line $line";
            trigger_error($error, E_USER_ERROR);
        };

        //use getter
        if('get' == substr($method, 0, 3))
        {
            $option = $this->getOptionByVariableName(
                lcfirst(substr($method, 3))
            );

            if(!$option)
                $undefinedError($method);

            return $option->getValue();
        }

        //use setter
        if('set' == substr($method, 0, 3))
        {
            $option = $this->getOptionByVariableName(
                lcfirst(substr($method, 3))
            );

            if(!$option)
                $undefinedError($method);

            $option->setValue($value);
            return $this;
        }

        //otherwise throw undefined method
        $undefinedError($method);
    }














} //class ends here