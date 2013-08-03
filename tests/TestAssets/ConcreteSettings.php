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
 * @package     ShiftContent
 * @subpackage  Tests
 */

/**
 * @namespace
 */
namespace ShiftTest\TestAssets\ShiftContentNew;

use ShiftContentNew\FieldType\AbstractSettings;

/**
 * Test field settings
 * This is a concrete implementation of field settings used for testing
 * abstract settings entity.
 *
 *
 * @category    Projectshift
 * @package     ShiftContent
 * @subpackage  Tests
 */
class ConcreteFieldSettings extends AbstractSettings
{

    /**
     * Concrete field-specific property
     * @var string
     */
    protected $concreteProperty;


    /**
     * To array
     * Returns array representation of settings.
     *
     * @return array|void
     */
    public function toArray()
    {
        $settingsArray = parent::toArray();
        $settingsArray['concreteProperty'] = $this->concreteProperty;
        return $settingsArray;
    }


    /**
     * Sets concrete property
     * @param string $value
     * @return void
     */
    public function setConcreteProperty($value)
    {
        $this->concreteProperty = $value;
    }


    /**
     * Get concrete property
     * @return string
     */
    public function getConcreteProperty()
    {
        return $this->concreteProperty;
    }




} //class ends here