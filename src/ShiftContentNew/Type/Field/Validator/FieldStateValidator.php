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

use Zend\Di\Locator;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\Exception\ConfigurationException;

/**
 * Field state validator
 * Validates that field is properly configured and has correct settings entity
 * defined that corresponds to configured field type.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldStateValidator extends AbstractValidator
{
    /**
     * Service locator instance
     * @var \Zend\Di\Locator
     */
    protected $locator;

    /**
     * Error message container
     * @vaer string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const FIELD_MISCONFIGURED = 'fieldMisconfigured';
    const FIELD_SETTINGS_MISCONFIGURED = 'fieldSettingsMisconfigured';

    /**
     * Error message templates
     * @var array
     */
    protected $_messageTemplates = array(
        self::FIELD_SETTINGS_MISCONFIGURED => "Field settings misconfigured.",
        self::FIELD_MISCONFIGURED =>
            "Field misconfigured. Please create fields with FieldFactory",
    );


    /**
     * Is valid
     * Validates that field type class actually exist.
     *
     * @param string $fieldType
     * @param \ShiftContentNew\Type\Type $editedType
     * @throws \ShiftContentNew\Exception\ConfigurationException
     * @return bool
     */
    public function isValid($field)
    {
        //check field
        if(!$field instanceof Field)
        {
            $error = "Field must be of ShiftContentNew\\Field\\Field type";
            throw new ConfigurationException($error);
        }

        //set value inside validator
        $this->setValue($field);

        $factory = 'ShiftContentNew\Type\Field\FieldFactory';
        $factory = $this->getLocator()->get($factory);

        //check type
        $typeClass = $field->getFieldType();
        $type = $factory->getFieldType($typeClass);
        if(!$typeClass || !$type)
        {
            $this->error(self::FIELD_MISCONFIGURED);
            return false;
        }

        //@todo move this to settings validator

        //check settings
        $settingsClass = $type->getSettingsClass();
        if($settingsClass && !$field->getSettings() instanceof $settingsClass)
        {
            $this->error(self::FIELD_SETTINGS_MISCONFIGURED);
            return false;
        }

        //return success if everything is ok
        return true;
    }



    /**
     * Set locator
     * Sets service locator instance.
     *
     * @param \Zend\Di\Locator $locator
     * @return \ShiftContentNew\Type\Field\Validator\FieldSettingValidator
     */
    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
        return $this;
    }


    /**
     * Get locator
     * Returns service locator instance.
     * @return \Zend\Di\Locator
     */
    public function getLocator()
    {
        return $this->locator;
    }





}//class ends here