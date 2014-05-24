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
 * @subpackage  Item
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Item\Validator;
use Zend\Validator\AbstractValidator;

use Zend\Di\Di as Locator;
use ShiftContentNew\ContentService;
use ShiftContentNew\Item\Item;

/**
 * Unique slug validator
 * This validator checks that item slug is unique across the system.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class UniqueSlugValidator extends AbstractValidator
{
    /**
     * Service locator instance
     * @var \Zend\Di\Di
     */
    protected $locator;

    /**
     * Content service instance
     * Used to search for items with slug
     *
     * @var \ShiftContentNew\ContentService
     */
    protected $contentService;

    /**
     * Error message container
     * @var string
     */
    protected $error;

    /**
     * Error key constant
     * @var string
     */
    const SLUG_NOT_UNIQUE = 'slugNotUnique';

    /**
     * Error message templates
     * @var array
     */
    protected $messageTemplates = array(
        self::SLUG_NOT_UNIQUE => "This slug is already in use",
    );


    /**
     * Is valid
     * Validates item date to return a boolean result
     *
     * @param string $status
     * @param \ShiftContentNew\Item\Item $status
     * @return bool
     */
    public function isValid($slug, Item $editedItem = null)
    {
        //set value inside validator
        $this->setValue($slug);

        if($editedItem && $slug == $editedItem->getSlug())
            return true;

        $item = $this->getContentService()->getItemBySlug($slug);
        if(!$item)
            return true;

        //otherwise it's an error
        $this->error(self::SLUG_NOT_UNIQUE);
        return false;
    }

    /**
     * Set locator
     * Sets service locator instance.
     *
     * @param \Zend\Di\Di $locator
     * @return \ShiftContentNew\Item\Validator\UniqueSlugValidator
     */
    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
        return $this;
    }


    /**
     * Get locator
     * Returns currently injected service locator.
     * @return \Zend\Di\Di
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * Set content service
     * Allows to inject arbitrary content service to search for items with
     * matching slug
     *
     * @param \ShiftContentNew\ContentService $contentService
     * @return \ShiftContentNew\Item\Validator\UniqueSlugValidator
     */
    public function setContentService(ContentService $contentService)
    {
        $this->contentService = $contentService;
        return $this;
    }


    /**
     * Get locator
     * Returns currently injected service locator.
     * @return \Zend\Di\Di
     */
    public function getContentService()
    {
        $serviceName = 'ShiftContentNew\ContentService';
        if(!$this->contentService)
            $this->contentService = $this->getLocator()->get($serviceName);

        return $this->contentService;
    }



}//class ends here