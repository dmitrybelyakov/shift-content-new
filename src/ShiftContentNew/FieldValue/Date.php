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
use DateTime;
use DateTimeZone;

/**
 * Date value
 *
 * @ORM\Entity
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  FieldValue
 */
class Date extends AbstractFieldValue
{
    /**
     * Property utc date value
     *
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     * @var \DateTime
     */
    protected $dateValue;


    /**
     * Set value
     * Sets property value of datetime type (may be initially a string in UTC).
     *
     * @param string|\DateTime $value
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldValue\Date
     */
    public function setValue($value)
    {
        //convert from string
        if(is_string($value))
        {
            try {
                $value = new DateTime($value, new DateTimeZone('UTC'));
            }
            catch(\Exception $exception)
            {
                throw new DomainException($exception->getMessage());
            }
        }

        //check timezone and set to UTC (adjusts time)
        if($value instanceof DateTime)
        {
            if('UTC' != $value->getTimezone()->getName())
                $value->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->dateValue = $value;
        return $this;
    }


    /**
     * Get value
     * Returns current value.
     *
     * @return \DateTime|void
     */
    public function getValue()
    {
        return $this->dateValue;
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