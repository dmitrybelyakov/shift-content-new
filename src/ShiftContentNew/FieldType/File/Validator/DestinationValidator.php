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

use ShiftCommon\Filesystem\Filesystem as Fs;

/**
 * Destination validator
 * Checks that destination specified for the file field exists and is writable.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class DestinationValidator extends AbstractValidator
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
    const DEST_UNWRITABLE = 'destinationUnwritable';
    const PARENT_UNWRITABLE = 'destinationParentUnwritable';
    const PARENT_NOT_EXIST = 'destinationParentNotExist';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::DEST_UNWRITABLE => "Destination is not writable",
        self::PARENT_UNWRITABLE => "Destination parent is not writable",
        self::PARENT_NOT_EXIST => "Destination parent does not exist",
    );


    /**
     * Is valid
     * Checks destination and its parent for existence and being writable.
     *
     * @param string $name
     * @return bool
     */
    public function isValid($path)
    {

        //check destination
        $result = Fs::checkDirectory($path);

        //destination exists and is writable
        if($result === true)
            return true;

        //destination exists and is not writable
        if($result == Fs::DIR_ERROR_PRIVILEGES)
        {
            $this->error(self::DEST_UNWRITABLE);
            return false;
        }

        //check parent
        $parent = substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR));
        $parentResult = Fs::checkDirectory($parent);

        //destination doesn't exist but parent is writable
        if($parentResult === true)
            return true;

        //destination does not exist, parent is not writable
        if($parentResult == Fs::DIR_ERROR_PRIVILEGES)
        {
            $this->error(self::PARENT_UNWRITABLE);
            return false;
        }


        //otherwise parent does not exist as well, which is an error
        $this->error(self::PARENT_NOT_EXIST);
        return false;


    }





}//class ends here