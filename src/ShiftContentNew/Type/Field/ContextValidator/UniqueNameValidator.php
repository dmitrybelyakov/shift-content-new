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
namespace ShiftContentNew\Type\Field\ContextValidator;
use Zend\Validator\AbstractValidator;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Exception\DomainException;

/**
 * Unique field name validator
 * This validator checks that field name is unique within content type
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class UniqueNameValidator extends AbstractValidator
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
    const FIELD_NAME_NOT_UNIQUE = 'nameNotUnique';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::FIELD_NAME_NOT_UNIQUE => "Field name is not unique"
    );


    /**
     * Is valid
     * Perform validation of field name in context of parent
     * content type.
     *
     * @param \ShiftContentNew\Type\Field\Field $field
     * @param \ShiftContentNew\Type\Type $type
     * @throws \ShiftContentNew\Exception\DomainException
     * @return bool
     */
    public function isValid($field, Type $type = null)
    {
        //check field type
        if(!$field instanceof Field)
        {
            $error = 'Field must be of ShiftContentNew\Type\Field\Field type';
            throw new DomainException($error);
        }

        //set value inside validator
        $name = (string) $field->getName();
        $this->setValue($name);

        if(!$type)
            return true;

        //validate field name against parent content type fields collection
        foreach($type->getFields() as $typeField)
        {
            if($name == $typeField->getName() && $field !== $typeField)
            {
                $this->error(self::FIELD_NAME_NOT_UNIQUE);
                return false;
            }
        }

        //otherwise return success
        return true;
    }




}//class ends here