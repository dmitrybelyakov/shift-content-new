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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use ShiftContentNew\FieldType\AbstractSettings;
use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\Field\Attribute\Attribute;
use ShiftContentNew\Exception\DomainException;

/**
 * Content type field
 * Represents single field of a content type, that contains field type
 * record, and a collection of filters and validators for this field.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_type_fields")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class Field
{
    /**
     * Field id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Parent content type.
     *
     * @ORM\ManyToOne(
     *  targetEntity="ShiftContentNew\Type\Type",
     *  inversedBy="fields"
     * )
     * @ORM\JoinColumn(
     *  name="typeId",
     *  referencedColumnName="id"
     * )
     * @var \ShiftContentNew\Type\Type
     */
    protected $type;

    /**
     * Field name
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @var string
     */
    protected $name;

    /**
     * Field property name
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @var string
     */
    protected $property;

    /**
     * Field type class
     *
     * @ORM\Column(type="string", nullable=false, unique=false)
     * @var string
     */
    protected $fieldType;

    /**
     * Field settings
     * @ORM\OneToOne(
     *  targetEntity="ShiftContentNew\FieldType\AbstractSettings",
     *  mappedBy="field",
     *  orphanRemoval=true,
     *  cascade={"persist", "remove"})
     * )
     * @var \ShiftContentNew\FieldType\AbstractSettings
     */
    protected $settings;

    /**
     * Field attributes
     * A collection of field filters and validators.
     *
     * @ORM\OneToMany(
     *  targetEntity="\ShiftContentNew\Type\Field\Attribute\Attribute",
     *  mappedBy="field",
     *  orphanRemoval=true,
     *  cascade={"persist", "remove"})
     * )
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $attributes;


    /**
     * Construct
     * Instantiates content field. May optionally populate from an
     * array data set.
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->attributes = new ArrayCollection;
        $this->fromArray($data);
    }


    /**
     * From array
     * Populates field from an array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Field
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
     * Returns an array representation of field.
     *
     * @return array
     */
    public function toArray()
    {
        $type = array(
            'id' => $this->id,
            'name' => $this->name,
            'property' => $this->property,
            'fieldType' => $this->fieldType,
            'settings' => null,
            'filters' => array(),
            'validators' => array(),
        );


        if($this->settings)
            $type['settings'] = $this->settings->toArray();

        $filters = $this->getFilters();
        foreach($filters as $filter)
            $type['filters'][] = $filter->toArray();

        $validators = $this->getValidators();
        foreach($validators as $validator)
            $type['validators'][] = $validator->toArray();

        return $type;
    }


    /**
     * Get id
     * Returns current field id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set type
     * Sets parent content type for the field
     *
     * @param \ShiftContentNew\Type\Type $type
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * Get type
     * Returns currently set type for the field.
     * @return \ShiftContentNew\Type\Type
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set name
     * Sets field name.
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }


    /**
     * Get name
     * Returns field name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set property
     * Sets field property.
     *
     * @param string $property
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function setProperty($property)
    {
        $this->property = lcfirst($property);
        return $this;
    }


    /**
     * Get property
     * Returns field property.
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }


    /**
     * Set field type
     * Sets field type.
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = (string) $fieldType;
        return $this;
    }


    /**
     * Get field type
     * Returns field type.
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }


    /**
     * Set settings
     * Sets field settings.
     *
     * @param \ShiftContentNew\FieldType\AbstractSettings $settings
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function setSettings(AbstractSettings $settings = null)
    {
        if($settings == null)
        {
            $this->settings = null;
            return $this;
        }

        $settings->setField($this);
        $this->settings = $settings;
        return $this;
    }


    /**
     * Get settings
     * Returns field settings.
     * @return \ShiftContentNew\FieldType\AbstractSettings
     */
    public function getSettings()
    {
        return $this->settings;
    }


    /**
     * Get attributes
     * Returns all field attributes (filters and validators) at once.
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes->toArray();
    }


    /**
     * Get filters
     * Returns an array of field filters.
     * @return array
     */
    public function getFilters()
    {
        $filters = array();
        foreach($this->attributes as $attribute)
        {
            if('filter' == $attribute->getType())
                $filters[] = $attribute;
        }
        return $filters;
    }


    /**
     * Add filter
     * Adds a filter to content field.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $filter
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function addFilter(Attribute $filter)
    {
        //check type
        if('filter' != $filter->getType())
            throw new DomainException('This is not a filter.');

        $filter->setField($this);
        $this->attributes->add($filter);
        return $this;
    }


    /**
     * Remove filter
     * Removes a filter from content field.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $filter
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function removeFilter(Attribute $filter)
    {
        $this->attributes->removeElement($filter);
        return $this;
    }


    /**
     * Get validators
     * Returns an array of field validators.
     * @return array
     */
    public function getValidators()
    {
        $validators = array();
        foreach($this->attributes as $attribute)
        {
            if('validator' == $attribute->getType())
                $validators[] = $attribute;
        }
        return $validators;
    }


    /**
     * Add validator
     * Adds a validator to content field.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $validator
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function addValidator(Attribute $validator)
    {
        //check type
        if('validator' != $validator->getType())
            throw new DomainException('This is not a validator.');

        $validator->setField($this);
        $this->attributes->add($validator);
        return $this;
    }


    /**
     * Remove filter
     * Removes a filter from content field.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $filter
     * @return \ShiftContentNew\Type\Field\Field
     */
    public function removeValidator(Attribute $validator)
    {
        $this->attributes->removeElement($validator);
        return $this;
    }




} //class ends here