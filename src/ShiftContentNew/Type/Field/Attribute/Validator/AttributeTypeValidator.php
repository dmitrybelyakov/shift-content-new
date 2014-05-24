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
namespace ShiftContentNew\Type\Field\Attribute\Validator;
use Zend\Validator\AbstractValidator;

/**
 * Attribute type validator
 * This validator checks that field attribute is either 'filter' or 'validator'
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeTypeValidator extends AbstractValidator
{

    /**
     * Error message container
     * @var string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const ATTRIBUTE_TYPE_INVALID = 'attributeTypeInvalid';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::ATTRIBUTE_TYPE_INVALID =>
            "Attribute type must be either 'filter' or 'validator'"
    );


    /**
     * Is valid
     * Validates field attribute type to return a boolean result.
     *
     * @param string $name
     * @return bool
     */
    public function isValid($type)
    {
        $validTypes = array('filter', 'validator');

        //set value inside validator
        $this->setValue($type);

        if(in_array($type, $validTypes))
            return true;

        //otherwise it's an error
        $this->error(self::ATTRIBUTE_TYPE_INVALID);
        return false;
    }



}//class ends here