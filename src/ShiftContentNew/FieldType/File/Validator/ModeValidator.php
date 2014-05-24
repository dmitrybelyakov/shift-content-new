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
namespace ShiftContentNew\FieldType\File\Validator;
use Zend\Validator\AbstractValidator;

use Zend\Di\Di as Locator;

/**
 * Mode validator
 * Checks that upload mode has a valid value.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class ModeValidator extends AbstractValidator
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
    const UPLOAD_MODE_INVALID = 'uplaodModeInvalid';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::UPLOAD_MODE_INVALID => "This is not a valid upload mode"
    );


    /**
     * Is valid
     * Validates upload mode setting to return a boolean result.
     *
     * @param $mode $name
     * @return bool
     */
    public function isValid($mode)
    {
        $validModes = array('uploadToDestination', 'createFolder');

        //set value inside validator
        $this->setValue($mode);

        if(in_array($mode, $validModes))
            return true;

        //otherwise it's an error
        $this->error(self::UPLOAD_MODE_INVALID);
        return false;
    }





}//class ends here