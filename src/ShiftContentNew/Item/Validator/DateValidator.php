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
 * @subpackage  Item
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Item\Validator;
use Zend\Validator\AbstractValidator;

/**
 * Item date validator
 * This validator checks that date is a valid DateTime object.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class DateValidator extends AbstractValidator
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
    const ITEM_DATE_INVALID = 'itemDateInvalid';
    const ITEM_DATE_NOT_UTC = 'itemDateNotUtc';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::ITEM_DATE_INVALID => "This is not a valid date",
        self::ITEM_DATE_NOT_UTC => "Date must be in UTC timezone",
    );


    /**
     * Is valid
     * Validates item date to return a boolean result
     *
     * @param string $status
     * @return bool
     */
    public function isValid($date)
    {
        //set value inside validator
        $this->setValue($date);

        //check type
        if(!$date instanceof \DateTime)
        {
            $this->error(self::ITEM_DATE_INVALID);
            return false;
        }

        //check timezone
        if('UTC' != $date->getTimezone()->getName())
        {
            $this->error(self::ITEM_DATE_NOT_UTC);
            return false;
        }

        //otherwise return success
        return true;
    }



}//class ends here