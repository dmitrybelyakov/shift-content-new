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

use Zend\Di\Di as Locator;

/**
 * Field type validator
 * Validates that given field type class name is configured and actually exists.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FieldTypeValidator extends AbstractValidator
{
    /**
     * Service locator instance
     * @var \Zend\Di\Di
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
    const NO_FIELD_TYPE = 'noFieldType';
    const FIELD_TYPE_NOT_CONFIGURED = 'fieldTypeNotConfigured';

    /**
     * Error message templates
     * @var array
     */
    protected $_messageTemplates = array(
        self::NO_FIELD_TYPE => "Field type class does not exist",
        self::FIELD_TYPE_NOT_CONFIGURED =>"That is not a configured field type",
    );


    /**
     * Is valid
     * Validates that field type class actually exist.
     *
     * @param string $fieldType
     * @param \ShiftContentNew\Type\Type $editedType
     * @return bool
     */
    public function isValid($fieldTypeClass, $editedType = null)
    {
        //set value inside validator
        $fieldTypeClass = (string) $fieldTypeClass;
        $this->setValue($fieldTypeClass);

        $factory = 'ShiftContentNew\Type\Field\FieldFactory';
        $factory = $this->getLocator()->get($factory);
        $types = $factory->getFieldTypes();

        $isConfigured = false;
        foreach($types as $type)
        {
            if($type == $fieldTypeClass)
            {
                $isConfigured = true;
                break;
            }
        }

        if(!$isConfigured)
        {
            $this->error(self::FIELD_TYPE_NOT_CONFIGURED);
            return false;
        }

        if(class_exists($fieldTypeClass))
            return true;

        //otherwise it's an error
        $this->error(self::NO_FIELD_TYPE);
        return false;
    }



    /**
     * Set locator
     * Sets service locator instance.
     *
     * @param \Zend\Di\Di $locator
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
     * @return \Zend\Di\Di
     */
    public function getLocator()
    {
        return $this->locator;
    }





}//class ends here