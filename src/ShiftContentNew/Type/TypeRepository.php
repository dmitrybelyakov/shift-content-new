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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use ShiftContentNew\Type\Type;
use ShiftContentNew\Exception\DatabaseException;

/**
 * Type repository
 * Encapsulates content type database level functionality.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class TypeRepository extends EntityRepository
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
     * @return \ShiftContentNew\Type\TypeRepository
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
        return $this;
    }


    /**
     * Get entity manager
     * Returns currently injected entity manager.
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }


    /**
     * Find by name
     * Returns content type by its unique name.
     * @param string $name
     * @return \ShiftContentNew\Type\Type
     */
    public function findByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }


    /**
     * Save type
     * Persists content type. May optionally flush transaction.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @throws \ShiftContentNew\Exception\DatabaseException
     * @param bool $flush
     * @return \ShiftContentNew\Type\Type
     */
    public function save(Type $type, $flush = true)
    {
        try
        {
            $this->getEntityManager()->persist($type);
            if($flush)
                $this->getEntityManager()->flush();
        }
        catch(\Exception $exception)
        {
            throw new DatabaseException(
                'Database error: ' . $exception->getMessage()
            );
        }

        return $type;
    }


    /**
     * Delete type
     * Removes content type. May optionally flush transaction.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @throws \ShiftContentNew\Exception\DatabaseException
     * @param bool $flush
     * @return void
     */
    public function delete(Type $type, $flush = true)
    {
        try
        {
            $deleteResult = $this->getEntityManager()->remove($type);
            if($flush)
                $this->getEntityManager()->flush();
        }
        catch(\Exception $exception)
        {
            throw new DatabaseException(
                'Database error: ' . $exception->getMessage()
            );
        }

        return $deleteResult;
    }



} //class ends here