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
use Zend\Filter\Alnum;

/**
 * Option variable validator
 * This validator check that option variable is correct variable name.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class OptionVariableValidator extends AbstractValidator
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
    const INVALID_VARIABLE_NAME = 'variableNameInvalid';

    /**
     * Error message templates
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_VARIABLE_NAME => "Invalid variable name for option"
    );


    /**
     * Is valid
     * Validates that variable name is valid
     *
     * @param string $name
     * @return bool
     */
    public function isValid($variableName)
    {
        //set value inside validator
        $variableName = (string) $variableName;
        $this->setValue($variableName);

        //Lowercase & filter
        $filter = new Alnum;
        $value = lcfirst($filter->filter($variableName));

        //Now validate
        if(!is_numeric(substr($value, 0, 1)) && $value == $variableName)
            return true;

        //Otherwise it's an error
        $this->error(self::INVALID_VARIABLE_NAME);
        return false;
    }



}//class ends here