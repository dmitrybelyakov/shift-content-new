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
 * Option type validator
 * This validator checks that attribute option value type is either 'bool',
 * 'string' or 'int'
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class OptionTypeValidator extends AbstractValidator
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
    const INVALID_VARIABLE_TYPE = 'invalidVariableType';

    /**
     * Error message templates
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_VARIABLE_TYPE =>
            "Variable type must be either 'bool', 'int' or 'string'"
    );


    /**
     * Is valid
     * Validates option variable type to return a boolean result.
     *
     * @param string $name
     * @return bool
     */
    public function isValid($type)
    {
        $validTypes = array('bool', 'int', 'string');

        //set value inside validator
        $this->setValue($type);

        if(in_array($type, $validTypes))
            return true;

        //otherwise it's an error
        $this->error(self::INVALID_VARIABLE_TYPE);
        return false;
    }



}//class ends here