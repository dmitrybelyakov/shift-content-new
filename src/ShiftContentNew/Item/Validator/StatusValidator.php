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
 * Item status validator
 * This validator checks that item publication status is valid.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class StatusValidator extends AbstractValidator
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
    const ITEM_STATUS_INVALID = 'itemStatusInvalid';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::ITEM_STATUS_INVALID => "Such item status is not allowed"
    );


    /**
     * Is valid
     * Validates item status to return a boolean result
     *
     * @param string $status
     * @return bool
     */
    public function isValid($status)
    {
        $validStatuses = array('published', 'pending', 'draft', 'deleted');

        //set value inside validator
        $this->setValue($status);

        if(in_array($status, $validStatuses))
            return true;

        //otherwise it's an error
        $this->error(self::ITEM_STATUS_INVALID);
        return false;
    }



}//class ends here