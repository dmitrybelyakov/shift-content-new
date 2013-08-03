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
namespace ShiftContentNew\FieldType\File;

use ShiftContentNew\FieldType\AbstractFieldType;

/**
 * File field type
 * Configures file field and related classes for processing.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FileType extends AbstractFieldType
{
    /**
     * Initialize field type
     * Sets configuration options for field type.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('File field');
        $this->setDescription('This field allows you to upload files');
        $this->setValueClass('ShiftContentNew\FieldValue\Text');
        $this->setEditorClass('Zend\Form\Element\File');
        $this->setSettingsClass('ShiftContentNew\FieldType\File\FileSettings');
        $this->setSettingsValidatorClass(
            'ShiftContentNew\FieldType\File\FileSettingsValidator'
        );
        $this->setValueProcessorClass(
            'ShiftContentNew\FieldType\File\FileValueProcessor'
        );
    }

} //class ends here