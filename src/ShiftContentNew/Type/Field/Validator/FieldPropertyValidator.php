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
namespace ShiftContentNew\Type\Field\Validator;
use Zend\Validator\AbstractValidator;
use Zend\I18n\Filter\Alnum;

/**
 * Field property
 * Validates that given string is a valid property name.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldPropertyValidator extends AbstractValidator
{
    /**
     * Error message container
     * @vaer string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const INVALID_PROPERTY_NAME = 'invalidPropertyName';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID_PROPERTY_NAME => "Property name is invalid",
    );


    /**
     * Is valid
     * Validates that property name is valid.
     *
     * @param string $fieldType
     * @return bool
     */
    public function isValid($property)
    {
        //set value inside validator
        $property = (string) $property;
        $this->setValue($property);

        //lowercase & filter
        $filter = new Alnum;
        $value = lcfirst($filter->filter($property));

        //now validate
        if(!is_numeric(substr($value, 0, 1)) && $value == $property)
            return true;

        //otherwise it's an error
        $this->error(self::INVALID_PROPERTY_NAME);
        return false;
    }




}//class ends here