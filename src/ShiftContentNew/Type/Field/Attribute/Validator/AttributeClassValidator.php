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

use Zend\Di\Di as Locator;

/**
 * Attribute class validator
 * This validator checks that field attribute class exists
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeClassValidator extends AbstractValidator
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
    const ATTRIBUTE_CLASS_INVALID = 'attributeClassInvalid';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::ATTRIBUTE_CLASS_INVALID =>
            "Attribute class does not exist"
    );


    /**
     * Is valid
     * Validates that attribute class exists.
     *
     * @param string $class
     * @return bool
     */
    public function isValid($class)
    {
        //set value inside validator
        $this->setValue($class);

        if(class_exists($class))
            return true;

        //otherwise it's an error
        $this->error(self::ATTRIBUTE_CLASS_INVALID);
        return false;
    }



}//class ends here