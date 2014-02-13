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
 * @subpackage  Field
 */

/**
 * @namespace
 */
namespace ShiftContentNew\FieldType;

use Zend\Di\Di as Locator;
use ShiftContentNew\FieldType\AbstractSettings;

/**
 * Abstract value processor
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Field
 */
abstract class AbstractValueProcessor
{

    /**
     * Service locator instance
     * @var \Zend\Di\Di
     */
    protected $locator;

    /**
     * Field specific settings
     * @var \ShiftContentNew\FieldType\AbstractSettings
     */
    protected $settings;


    /**
     * Construct
     * Instantiates value processor.
     *
     * @param Locator $locator
     */
    public function __construct(Locator $locator = null)
    {
        $this->locator = $locator;
    }


    /**
     * Set settings
     * Sets field-specific settings entity into value processor.
     *
     * @param \ShiftContentNew\FieldType\AbstractSettings $settings
     * @return mixed
     */
    public function setSettings(AbstractSettings $settings)
    {
        $this->settings = $settings;
        return $this;
    }


    /**
     * Returns currently set settings.
     *
     * @return \ShiftContentNew\FieldType\AbstractSettings
     */
    public function getSettings()
    {
        return $this->settings;
    }


    /**
     * Perform value processing
     * Validate and process value.
     *
     * @param $value
     * @param null $request
     * @return mixed
     */
    abstract public function process();


    /**
     * Rollback
     * Rollback processing result.
     *
     * @param $value
     * @return mixed
     */
    abstract public function rollback();


} //class ends here