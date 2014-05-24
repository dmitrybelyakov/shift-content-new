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
namespace ShiftContentNew\Type\Field\Validator;
use Zend\Validator\AbstractValidator;

use ShiftCommon\Model\Entity\EntityValidator;
use ShiftCommon\Model\Validation\Result;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\FieldType\FieldTypeFactory;
use ShiftContentNew\FieldType\AbstractSettings as Settings;
use ShiftContentNew\Exception\DomainException;

/**
 * Field settings validator
 * Is a wrapper-factory that examines parent field type to instantiate
 * corresponding validator and delegate validation.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldSettingsValidator extends EntityValidator
{
    /**
     * Field type factory
     * Used to get appropriate validator based on field type and delegate.
     *
     * @var \ShiftContentNew\FieldType\FieldTypeFactory
     */
    protected $fieldTypeFactory;


    /**
     * Initialize
     * Does nothing in this implementation.
     * See validation method below for the actual logic.
     *
     * @return EntityValidator|void
     */
    public function init()
    {
        return;
    }


    /**
     * Validate
     * Here we override validation method from parent validator to
     * detect parent field type and instantiate appropriate validator and then
     * delegate validation to it.
     *
     *
     * @param \ShiftContentNew\FieldType\AbstractSettings $settings
     * @param \ShiftContentNew\Type\Field\Field $field
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftCommon\Model\Validation\Result
     */
    public function validate(Settings $settings = null, Field $field = null)
    {
        //check context
        if(!$field)
        {
            $error = "Parent field not found. Can't detect settings validator ";
            $error .= "to delegate.";
            throw new DomainException($error);
        }

        //just return if field has no type as we can't detect how to validate
        $fieldTypeClass = $field->getFieldType();
        if(!$fieldTypeClass)
            return new Result;


        //check if we don't need settings
        $fieldType = new $fieldTypeClass;
        $settingsClass = $fieldType->getSettingsClass();
        if(!$settingsClass)
            return new Result;

        //check that we do have settings (if we need to)
        if(!empty($settingsClass) && !$settings)
        {
            $result = new Result;
            $result->addErrors(array('_notEmpty' => 'Settings are empty.'));
            return $result;
        }

        //now create settings validator and delegate
        $fieldTypeFactory = $this->getFieldTypeFactory();
        $validator = $fieldTypeFactory->getSettingsValidator($fieldType);
        $result = $validator->validate($settings, $field);
        return $result;
    }


    /**
     * Set field type factory
     * Allows to inject field type factory to be used for validation.
     *
     * @param \ShiftContentNew\FieldType\FieldTypeFactory $fieldTypeFactory
     * @return \ShiftContentNew\Type\Field\Validator\FieldSettingsValidator
     */
    public function setFieldTypeFactory(FieldTypeFactory $fieldTypeFactory)
    {
        $this->fieldTypeFactory = $fieldTypeFactory;
        return $this;
    }


    /**
     * Get field type factory
     * Returns injected field type factory if there is one or gets it
     * from locator.
     *
     * @throws \ShiftContentNew\Exception\DomainException
     * @return \ShiftContentNew\FieldType\FieldTypeFactory
     */
    public function getFieldTypeFactory()
    {
        if(!$this->fieldTypeFactory)
        {
            $this->fieldTypeFactory = $this->sm->get(
                'ShiftContentNew\FieldType\FieldTypeFactory'
            );
        }

        return $this->fieldTypeFactory;
    }


}//class ends here