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
 * @subpackage  FieldValue
 */

/**
 * @namespace
 */
namespace ShiftContentNew\FieldValue;
use Doctrine\ORM\Mapping as ORM;
use ShiftContentNew\FieldValue\AbstractFieldValue;
use ShiftContentNew\Exception\DomainException;

/**
 * String value
 *
 * @ORM\Entity
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  FieldValue
 */
class String extends AbstractFieldValue
{
    /**
     * Property string value
     * @ORM\Column(type="string", unique=false, nullable=true, length=255)
     * @var string
     */
    protected $stringValue;


    /**
     * Set value
     * Sets property value of string type.
     *
     * @param string $value
     * @return \ShiftContentNew\FieldValue\String
     */
    public function setValue($value)
    {
        if(strlen($value) > 255)
            throw new DomainException('String too long. Use text instead');

        $this->stringValue = (string) $value;
        return $this;
    }


    /**
     * Get value
     * Returns current value.
     *
     * @return string|void
     */
    public function getValue()
    {
        return $this->stringValue;
    }


    /**
     * To array
     * Returns an array representation of value.
     *
     * @return array
     */
    public function toArray()
    {
        $property = parent::toArray();
        $property['value'] = $this->getValue();
        return $property;
    }


} //class ends here