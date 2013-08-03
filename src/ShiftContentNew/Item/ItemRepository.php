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
namespace ShiftContentNew\Item;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use ShiftContentNew\Item\Item;
use ShiftContentNew\Exception\DatabaseException;


/**
 * Content item repository
 * Encapsulates all database interaction logic into one layer.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class ItemRepository extends EntityRepository
{
    /**
     * Doctrine entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * Set entity manager
     * Injects arbitrary entity manager instance.
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return \ShiftContentNew\Item\ItemRepository
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
        return $this;
    }


    /**
     * Get entity manager
     * Returns doctrine entity manager instance.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }


    /**
     * Save
     * Persists an item. May optionally flush transaction after persist.
     * Returns saved item on success.
     *
     * @param \ShiftContentNew\Item\Item $item
     * @param bool $flush
     * @throws \ShiftContentNew\Exception\DatabaseException
     * @return \ShiftContentNew\Item\Item
     */
    public function save(Item $item, $flush = true)
    {
        try
        {
            $this->_em->persist($item);
            if($flush)
                $this->_em->flush();
        }
        catch(\Exception $exception)
        {
            $message = "Database error: " . $exception->getMessage();
            throw new DatabaseException($message);
        }

        //return on success
        return $item;
    }


    /**
     * Delete
     * Removes an item. May optionally flush transaction after persist.
     * Returns saved item on success.
     *
     * @param \ShiftContentNew\Item\Item $item
     * @param bool $flush
     * @throws \ShiftContentNew\Exception\DatabaseException
     * @return void
     */
    public function delete(Item $item, $flush = true)
    {
        try
        {
            $this->_em->remove($item);
            if($flush)
                $this->_em->flush();
        }
        catch(\Exception $exception)
        {
            $message = "Database error: " . $exception->getMessage();
            throw new DatabaseException($message);
        }

        return;
    }

} //class ends here