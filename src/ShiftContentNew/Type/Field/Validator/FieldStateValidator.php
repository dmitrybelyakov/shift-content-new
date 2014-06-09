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

use Zend\ServiceManager\ServiceManager;
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
    const FIELD_MISCONFIGURED = 'fieldMisconfigured';
    const FIELD_SETTINGS_MISCONFIGURED = 'fieldSettingsMisconfigured';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::FIELD_SETTINGS_MISCONFIGURED => "Field settings misconfigured.",
        self::FIELD_MISCONFIGURED =>
            "Field misconfigured. Please create fields with FieldFactory",
    );

    /**
     * Set service manager
     * Sets service manager instance.
     *
     * @param \Zend\ServiceManager\ServiceManager $sm
     * @return \ShiftContentNew\Type\Field\Validator\FieldStateValidator
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
        return $this;
    }


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
        $factory = $this->sm->get($factory);

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









}//class ends here