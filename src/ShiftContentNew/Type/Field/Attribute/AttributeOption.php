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
use Zend\Filter\Alnum as VariableFilter;
use ShiftContentNew\Exception\DomainException;

/**
 * Content type field attribute option
 * Represents and option value for field or validator.
 *
 * @ORM\Entity
 * @ORM\Table(name="shiftcontentnew_type_field_attribute_options")
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeOption
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
     * Parent attribute
     * @ORM\ManyToOne(
     *  targetEntity="ShiftContentNew\Type\Field\Attribute\Attribute",
     *  inversedBy="options"
     * )
     * @ORM\JoinColumn(
     *  name="attributeId",
     *  referencedColumnName="id"
     * )
     * @var \ShiftContentNew\Type\Field\Attribute\Attribute
     */
    protected $attribute;

    /**
     * Option name
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $name;

    /**
     * Variable name
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $variable;

    /**
     * Value type
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $type;


    /**
     * Option value
     *
     * @ORM\Column(type="string", nullable=false)
     * @var mixed
     */
    protected $value;


    /**
     * An array of valid value types
     * @var array
     */
    private $valueTypes = array('string', 'int', 'bool');


    // ------------------------------------------------------------------------

    /*
     * Public API
     */


    /**
     * Construct
     * Instantiates attribute option. May optionally populate option
     * from an array data set.
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
     * Populates attribute option from array data set.
     *
     * @param array $data
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
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
     * Returns an array representation of attribute option.
     *
     * @return array
     */
    public function toArray()
    {
        $option = array(
            'id' => $this->id,
            'name' => $this->name,
            'variable' => $this->variable,
            'type' => $this->type,
            'value' => $this->value,
        );
        return $option;
    }


    /**
     * Get id
     * Returns option id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set attribute
     * Sets parent attribute for the option.
     *
     * @param \ShiftContentNew\Type\Field\Attribute\Attribute $attribute
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }


    /**
     * Get attribute
     * Returns parent attribute for the option.
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }


    /**
     * Set option name
     * Sets attribute option name
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Get option name
     * Returns attribute option name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set variable name
     * Sets attribute variable name that will be passed to attribute at
     * instantiation. Variable will be lowercased and all spaces stripped.
     *
     * @param string $variable
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
     */
    public function setVariable($variable)
    {
        $variable = lcfirst($variable);

        //validate variable name
        $filter = new VariableFilter;
        $filter->setAllowWhiteSpace(false);
        $filtered = $filter->filter($variable);
        if(is_numeric(substr($variable, 0, 1)) || $filtered !== $variable)
            throw new DomainException('Invalid variable name');

        //otherwise set
        $this->variable = $variable;
        return $this;
    }


    /**
     * Get variable name
     * Returns attribute option variable name.
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }


    /**
     * Set value type
     * Sets attribute option value type to be used for value validation when
     * setting option value.
     *
     * @param string $type
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
     */
    public function setType($type = null)
    {
        if($type == null)
        {
            $this->type = null;
            return;
        }

        $type = strtolower($type);
        if(!in_array($type, $this->valueTypes))
            throw new DomainException('Invalid value type');

        $this->type = strtolower($type);
        return $this;
    }


    /**
     * Get value type
     * Returns attribute option value type.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set value
     * Sets attribute option value and validates its type.
     *
     * @param mixed $value
     * @throw \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\Type\Field\Attribute\AttributeOption
     */
    public function setValue($value)
    {
        //validate type
        $type = $this->getType();
        switch($type)
        {
            case 'int':
                $value = (int) $value;
                break;
            case 'string':
                $value = (string) $value;
                break;
            case 'bool':
                $value = (bool) $value;
                break;
            default:
                throw new DomainException("Invalid value type");
        }

        $this->value = $value;
        return $this;
    }


    /**
     * Get value
     * Returns currently set option value
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }









} //class ends here