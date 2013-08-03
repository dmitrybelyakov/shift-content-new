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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use ShiftContentNew\Item\Meta;
use ShiftContentNew\FieldValue\AbstractFieldValue;
use ShiftContentNew\Exception\DomainException;


/**
 * Content item
 * Represent a single content item of arbitrary type.
 *
 * @ORM\Entity(repositoryClass="ShiftContentNew\Item\ItemRepository")
 * @ORM\Table(name="shiftcontentnew_items")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class Item extends Meta
{
    /**
     * Item properties
     *
     * @ORM\OneToMany(
     *  targetEntity="ShiftContentNew\FieldValue\AbstractFieldValue",
     *  mappedBy="item",
     *  orphanRemoval=true,
     *  cascade={"persist", "remove"})
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $properties = array();


    // ------------------------------------------------------------------------

    /*
     * Public API
     */


    /**
     * Construct
     * Instantiates content item.
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->properties = new ArrayCollection;
        parent::__construct(); //no data here
        $this->fromArray($data);//used here instead
    }


    /**
     * Add property
     * Adds custom item property.
     *
     * @param \ShiftContentNew\FieldValue\AbstractFieldValue $property
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Item\Item
     */
    public function addProperty(AbstractFieldValue $property)
    {
        //check property name
        if(!$property->getName())
            throw new DomainException('Property must have a name');

        //check presence
        foreach($this->properties as $existing)
        {
            $name = $property->getName();
            $error = "Property '" . $name  . "' already exists";
            if($property->getName() == $existing->getName())
                throw new DomainException($error);
        }

        $property->setItem($this);
        $this->properties->add($property);
        return $this;
    }


    /**
     * Remove property
     * Removes custom item property.
     *
     * @param \ShiftContentNew\FieldValue\AbstractFieldValue $property
     * @return \ShiftContentNew\Item\Item
     */
    public function removeProperty(AbstractFieldValue $property)
    {
        $this->properties->removeElement($property);
        return $this;
    }


    /**
     * Get properties collection
     * Returns doctrine collection of item properties.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropertiesCollection()
    {
        return $this->properties;
    }


    /**
     * Get property by name
     * Returns item property by its name.
     *
     * @param string $name
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue | void
     */
    public function getPropertyByName($name)
    {
        foreach($this->properties as $property)
        {
            if($name == $property->getName())
                return $property;
        }
    }


    /**
     * From array
     * Here we extend parent fromArray method to allow setting custom item
     * properties from array as well
     *
     * @param array $data
     * @return \ShiftContentNew\Item\Item
     */
    public function fromArray(array $data)
    {
        //populate meta from array
        parent::fromArray($data);

        //populate custom properties from array
        foreach($data as $property => $value)
        {
            $methodName = 'set' . ucfirst($property); //use setter if exists
            $propertyObject = $this->getPropertyByName($property);
            if($propertyObject && !method_exists($this, $methodName))
                $propertyObject->setValue($value);
        }

        return $this;
    }


    /**
     * To array
     * Returns an array representation of content item.
     * @return array
     */
    public function toArray()
    {
        $itemArray = parent::toArray();
        foreach($this->properties as $property)
        {
            $value = $property->getValue();
            if(is_object($value) & method_exists($value, 'toArray'))
                $value = $value->toArray();

            $name = $property->getName();
            $itemArray[$name] = $value;
        }

        return $itemArray;
    }


    // ------------------------------------------------------------------------

    /*
     * Magic property accessors
     */


    /**
     * Call
     * Magic overloading method to call getters and setters on item properties.
     * Will scan for requested field and get/set if found.
     *
     * @param string $method
     * @param array $value
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
            $property = $this->getPropertyByName(
                lcfirst(substr($method, 3))
            );

            if(!$property)
                $undefinedError($method);

            return $property->getValue();
        }

        //use setter
        if('set' == substr($method, 0, 3))
        {
            $property = $this->getPropertyByName(
                lcfirst(substr($method, 3))
            );

            if(!$property)
                $undefinedError($method);

            $property->setValue($value);
            return $this;
        }

        //otherwise throw undefined method
        $undefinedError($method);
    }


} //class ends here