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
namespace ShiftContentNew\Type;

use Zend\Di\Di as Locator;
use ShiftContentNew\Type\TypeService;

/**
 * Content type API service
 * Responsible for processing api requests for content types management.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class TypeApiService
{
    /**
     * Service locator instance
     * @var \Zend\Di\Di
     */
    protected $locator;

    /**
     * Type service instance
     * @var \ShiftContentNew\Type\TypeService
     */
    protected $typeService;


    /**
     * Construct
     * Instantiates content type service. Requires an instance if service
     * locator to be injected.
     *
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }


    /**
     * Set type service
     * Allows you to inject arbitrary content type service.
     *
     * @param \ShiftContentNew\Type\TypeService $typeService
     * @return \ShiftContentNew\Type\TypeApiService
     */
    public function setTypeService(TypeService $typeService)
    {
        $this->typeService = $typeService;
        return $this;
    }


    /**
     * Get type service
     * Checks is we already have type service injected and obtains one
     * from locator if not.
     *
     * @return \ShiftContentNew\Type\TypeService
     */
    public function getTypeService()
    {
        $service = 'ShiftContentNew\Type\TypeService';
        if(!$this->typeService)
            $this->typeService = $this->locator->get($service);

        return $this->typeService;
    }





} //class ends here