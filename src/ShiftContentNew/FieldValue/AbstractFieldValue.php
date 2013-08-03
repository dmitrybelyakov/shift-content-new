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
namespace ShiftContentNew\FieldValue;

use Doctrine\ORM\Mapping as ORM;
use ShiftContentNew\Item\Item;

/**
 * Item property
 * Represents a custom content item property that links to the corresponding
 * property value of arbitrary type.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_item_properties")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="valueType", type="string")
 * @ORM\DiscriminatorMap({
 *  "string" = "ShiftContentNew\FieldValue\String",
 *  "text" = "ShiftContentNew\FieldValue\Text",
 *  "date" = "ShiftContentNew\FieldValue\Date",
 *  "mediaItem" = "ShiftContentNew\FieldValue\MediaItem",
 *  "mediaAlbum" = "ShiftContentNew\FieldValue\MediaAlbum",
 * })
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
abstract class AbstractFieldValue
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
     * Property name
     *
     * @ORM\Column(type="string", unique=false, nullable=true)
     * @var string
     */
    protected $name;

    /**
     * Field parent item
     *
     * @ORM\ManyToOne(
     *  targetEntity="ShiftContentNew\Item\Item",
     *  inversedBy="fields")
     * @ORM\JoinColumn(name="itemId", referencedColumnName="id")
     *
     * @var \ShiftContentNew\Item\Item
     */
    protected $item;


    /**
     * Construct
     * Instantiates property. May optionally populate from array data set at
     * instantiation time.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->fromArray($data);
    }


    /**
     * From array
     * Populates property from an array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue
     */
    public function fromArray(array $data = array())
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
     * Returns an array representation of property value.
     * This should be extended in concrete field values classes.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name
        );
    }

    /**
     * Set value
     * Implement this in concrete field value classes.
     *
     * @param mixed $value
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue
     */
    abstract public function setValue($value);

    /**
     * Implement this in concrete field value classes.
     * @return mixed
     */
    abstract public function getValue();


    /**
     * Get id
     * Returns property id.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set item
     * Sets parent content item for the property.
     * @param \ShiftContentNew\Item\Item $item
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
        return $this;
    }


    /**
     * Get item
     * Returns currently set parent content item.
     * @return \ShiftContentNew\Item\Item
     */
    public function getItem()
    {
        return $this->item;
    }


    /**
     * Set name
     * Sets property name. Thw first character will be lowercased;
     *
     * @param string $name
     * @return \ShiftContentNew\FieldValue\AbstractFieldValue
     */
    public function setName($name)
    {
        $this->name = lcfirst($name);
        return $this;
    }


    /**
     * Get name
     * Returns property name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


} //class ends here