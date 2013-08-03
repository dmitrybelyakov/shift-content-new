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
namespace ShiftContentNew\Type\Field;

use ShiftCommon\Model\Entity\EntityValidator;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

use Zend\Validator\Alnum;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;
use ShiftContentNew\Type\Field\ContextValidator\UniqueNameValidator;
use ShiftContentNew\Type\Field\ContextValidator\UniquePropertyValidator;
use ShiftContentNew\Type\Field\Validator\FieldPropertyValidator;

/**
 * Content type field validator
 * This encapsulates rules for validating content types fields
 * before persistence.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldValidator extends EntityValidator
{
    /**
     * Init
     * Initializes validator and defines rules for validating entity
     * properties.
     *
     * @return EntityValidator|void
     */
    public function init()
    {
        //field state validator (checks field assembly correctness)
        $this->addStateValidator(
            'configuration',
            'ShiftContentNew\Type\Field\Validator\FieldStateValidator'
        );

        //parent type
        $this->addEntity('type');
        $this->type->notEmpty();


        //name
        $this->addProperty('name');
        $this->name->addFilter(new StripTags);
        $this->name->addFilter(new StringTrim);
        $this->name->addValidator(new NotEmpty);
        $this->name->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->name->addValidator(new StringLength(
            array('min' => '3', 'max' => 250)
        ));

        //property (see context validation below as well)
        $this->addProperty('property');
        $this->property->addFilter(new StripTags);
        $this->property->addFilter(new StringTrim);
        $this->property->addValidator(new NotEmpty);
        $this->property->addValidator(new FieldPropertyValidator);

        //field type
        $this->addProperty('fieldType');
        $this->fieldType->addFilter(new StripTags);
        $this->fieldType->addFilter(new StringTrim);
        $this->fieldType->addValidator(new NotEmpty);
        $this->fieldType->addValidator(
            'ShiftContentNew\Type\Field\Validator\FieldTypeValidator'
        );

        //field settings
        $this->addEntity('settings');
        $this->settings->setEntityValidator(
            'ShiftContentNew\Type\Field\Validator\FieldSettingsValidator'
        );

        //attributes
        $this->addCollection('attributes');
        $this->attributes->setElementValidator(
            'ShiftContentNew\Type\Field\Attribute\AttributeValidator'
        );
    }


    /**
     * Validate
     * Here we extend standard validation method to do some checks in context
     * of parent content type (is it exists)
     *
     * @param mixed|null $entity
     * @param null $context
     * @return \ShiftCommon\Model\Validation\Result
     */
    public function validate($entity, $context = null)
    {
        $result = parent::validate($entity, $context);

        if(!$context)
            return $result;

        //validate name uniqueness within parent type
        $validator = new UniqueNameValidator;
        if(!$validator->isValid($entity, $context))
        {
            $result->addErrors(
                array('name' => $validator->getMessages())
            );
        }


        //validate property uniqueness within parent type
        $validator = new UniquePropertyValidator;
        if(!$validator->isValid($entity, $context))
        {
            $result->addErrors(
                array('property' => $validator->getMessages())
            );
        }

        return $result;
    }



} //class ends here