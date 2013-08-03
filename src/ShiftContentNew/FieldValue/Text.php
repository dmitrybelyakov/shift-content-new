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

/**
 * Text value
 *
 * @ORM\Entity
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  FieldValue
 */
class Text extends AbstractFieldValue
{
    /**
     * Property text value
     *
     * @ORM\Column(type="text", unique=false, nullable=true)
     * @var string
     */
    protected $textValue;


    /**
     * Set value
     * Sets property value of text type.
     *
     * @param string $value
     * @return \ShiftContentNew\FieldValue\Text
     */
    public function setValue($value)
    {
        $this->textValue = (string) $value;
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
        return $this->textValue;
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