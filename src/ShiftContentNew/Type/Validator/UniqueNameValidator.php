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
namespace ShiftContentNew\Type\Validator;
use Zend\Validator\AbstractValidator;

use Zend\ServiceManager\ServiceManager;

/**
 * Unique content type name validator
 * This validator checks if content type name is unique.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class UniqueNameValidator extends AbstractValidator
{

    /**
     * Service manager instance
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * Error message container
     * @vaer string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const NAME_NOT_UNIQUE = 'nameNotUnique';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::NAME_NOT_UNIQUE => "Such content type already exists"
    );

    /**
     * Set service manager
     * Sets service manager instance
     *
     * @param \Zend\ServiceManager\ServiceManager $sm
     * @return \ShiftContentNew\Type\Validator\UniqueNameValidator
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
        return $this;
    }


    /**
     * Is valid
     * Performs validation of a string to return a boolean result
     *
     * @param string $name
     * @param \ShiftContentNew\Type\Type $editedType
     * @return bool
     */
    public function isValid($name, $editedType = null)
    {
        //Set value inside validator
        $this->setValue($name);

        //edited type must be persisted to pass
        if($editedType && $editedType->getId() &&
            $name == $editedType->getName())
        {
            return true;
        }

        $service = $this->sm->get('ShiftContentNew\Type\TypeService');
        $type = $service->getTypeByName($name);



        if(!$type)
            return true;

        //Otherwise it's an error
        $this->error(self::NAME_NOT_UNIQUE);
        return false;
    }




}//class ends here