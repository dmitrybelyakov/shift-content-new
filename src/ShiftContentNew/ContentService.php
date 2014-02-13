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
 */

/**
 * @namespace
 */
namespace ShiftContentNew;

use Zend\Di\Di as Locator;
use ShiftContentNew\Item\ItemRepository;


/**
 * Content service
 * Provides application-level api to content management functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 */
class ContentService
{
    /**
     * Service locator instance
     * @var \Zend\Di\Di
     */
    protected $locator;


    /**
     * Content items repository
     * @var \ShiftContentNew\Item\ItemRepository
     */
    protected $repository;


    /**
     * Construct
     * Instantiates content service.
     * Requires an instance of service locator.
     *
     * @param \Zend\Di\Di $locator
     * @return void
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }


    /**
     * Set repository
     * Allows to inject arbitrary item repository to be used within service.
     *
     * @param \ShiftContentNew\Item\ItemRepository $repository
     * @return \ShiftContentNew\ContentService
     */
    public function setRepository(ItemRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }


    /**
     * Get repository
     * Checks if we already have a repository injected and obtains one from
     * entity manager.
     *
     * @return ItemRepository
     */
    public function getRepository()
    {
        if(!$this->repository)
        {
            $em = $this->locator->get('Doctrine')->getEntityManager();
            $this->repository = $em->getRepository('ShiftContentNew\Item\Item');
        }

        return $this->repository;
    }


    /**
     * Get item
     * Returns content item found by its unique id.
     *
     * @param int $id
     * @return \ShiftContentNew\Item\Item | void
     */
    public function getItem($id)
    {
        return $this->getRepository()->findOneById($id);
    }


    /**
     * Get item by slug
     * Returns content item found by its unique slug
     *
     * @param string $slug
     * @return \ShiftContentNew\Item\Item | void
     */
    public function getItemBySlug($slug)
    {
        return $this->getRepository()->findBySlug($slug);
    }


} //class ends here