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
namespace ShiftContentNew\Type;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use ShiftContentNew\Type\Field\Field;

/**
 * Content type
 * Is a collection of rules and settings that define how certain items are
 * handled. This may include editor generation, content validation and
 * processing.
 *
 * @ORM\Entity(repositoryClass="ShiftContentNew\Type\TypeRepository")
 * @ORM\Table(name="shiftcontentnew_types")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class Type
{
    /**
     * Item id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Content type name
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @var string
     */
    protected $name;

    /**
     * Content type description
     *
     * @ORM\Column(type="string", nullable=true, unique=false)
     * @var string
     */
    protected $description = null;


    /**
     * Content type fields collection
     * Each field defines name, storage logic and editor type tu use for
     * the particular field.
     *
     * @ORM\OneToMany(
     *  targetEntity="\ShiftContentNew\Type\Field\Field",
     *  mappedBy="type",
     *  orphanRemoval=true,
     *  cascade={"persist", "remove"})
     * )
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $fields = array();


    /**
     * Construct
     * Instantiates content type. May optionally populate from array data set.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->fields = new ArrayCollection;
        $this->fromArray($data);
    }


    /**
     * From array
     * Populates type from an array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\Type\Type
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
     * returns an array representation of type.
     * @return array
     */
    public function toArray()
    {
        $type = array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'fields' => array()
        );

        foreach($this->fields as $field)
            $type['fields'][] = $field->toArray();

        return $type;
    }


    /**
     * Get id
     * Returns current content type id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set name
     * Sets content type name.
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Type
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }


    /**
     * Get name
     * Returns current content type name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set description
     * Sets content type description.
     *
     * @param string $description
     * @return \ShiftContentNew\Type\Type
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }


    /**
     * Get description
     * Returns current content type description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get fields
     * Returns an array of content type fields.
     * @return array
     */
    public function getFields()
    {
        return $this->fields->toArray();
    }


    /**
     * Get field
     * Returns single type field by its property name.
     *
     * @param string $property
     * @return \ShiftContentNew\Type\Field\Field | void
     */
    public function getField($property)
    {
        $field = null;
        foreach($this->fields as $currentField)
        {
            if($property == $currentField->getProperty())
            {
                $field = $currentField;
                break;
            }
        }

        return $field;
    }


    /**
     * Add field
     * Adds a field to content type.
     *
     * @param \ShiftContentNew\Type\Field\Field $field
     * @return \ShiftContentNew\Type\Type
     */
    public function addField(Field $field)
    {
        $field->setType($this);
        $this->fields->add($field);
        return $this;
    }


    /**
     * Remove field
     * Removes a field from content type.
     *
     * @param \ShiftContentNew\Type\Field\Field $field
     * @return \ShiftContentNew\Type\Type
     */
    public function removeField(Field $field)
    {
        $this->fields->removeElement($field);
        return $this;
    }


} //class ends here