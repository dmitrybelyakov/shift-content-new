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

use Doctrine\ORM\Mapping as ORM;
use ShiftContentNew\Type\Field\Field;

/**
 * Abstract settings
 * This is a base entity to be extended to implement concrete field settings.
 *
 * todo: single table inheritance may cause setting column collisions.
 * todo: maybe joined will be better although performance costly.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_field_settings")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="field", type="string")
 * @ORM\DiscriminatorMap({
 *  "abstract" = "ShiftContentNew\FieldType\AbstractSettings"
 * })
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Field
 */
abstract class AbstractSettings
{
    /**
     * Settings record id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Parent content type field
     *
     * @ORM\OneToOne(
     *  targetEntity="ShiftContentNew\Type\Field\Field",
     *  inversedBy="settings"
     * )
     * @ORM\JoinColumn(name="fieldId", referencedColumnName="id")
     * @var \ShiftContentNew\Type\Field\Field
     */
    protected $field;

    /**
     * Construct
     * Instantiates settings record. May optionally populate from array data
     * set upon creation.
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
     * Populates field from an array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\FieldType\AbstractSettings
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
     * Implement this in your concrete settings entity.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id
        );
    }


    /**
     * Get id
     * Returns settings record id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set field
     * Sets parent field for settings entity.
     *
     * @param \ShiftContentNew\Type\Field\Field $field
     * @return \ShiftContentNew\FieldType\AbstractSettings
     */
    public function setField(Field $field)
    {
        $this->field = $field;
        return $this;
    }


    /**
     * Get field
     * Returns currently set field.
     * @return \ShiftContentNew\Type\Field\Field | void
     */
    public function getField()
    {
        return $this->field;
    }




} //class ends here