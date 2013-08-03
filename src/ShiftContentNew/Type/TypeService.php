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

use Zend\Di\Locator;
use ShiftContentNew\Type\Type;
use ShiftContentNew\Type\TypeRepository;
use ShiftContentNew\Type\Field\Field;
use ShiftContentNew\FieldType\FieldTypeFactory;

/**
 * Content type service
 * Responsible for creation and management of content types.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class TypeService
{
    /**
     * Service locator instance
     * @var \Zend\Di\Locator
     */
    protected $locator;

    /**
     * Type repository instance
     * @var \ShiftContentNew\Type\TypeRepository
     */
    protected $repository;


    // ------------------------------------------------------------------------

    /*
     * Public API
     */


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
     * Get types
     * Returns an array of existing content types.
     * @return array
     */
    public function getTypes()
    {
        return $this->getRepository()->findAll();
    }


    /**
     * Get type
     * returns single content type by its numeric id.
     *
     * @param int $id
     * @return \ShiftContentNew\Type\Type
     */
    public function getType($id)
    {
        return $this->getRepository()->findOneById($id);
    }


    /**
     * Get type by name
     * Returns content type by name
     *
     * @param string $name
     * @return \ShiftContentNew\Type\Type
     */
    public function getTypeByName($name)
    {
        return $this->getRepository()->findByName($name);
    }


    /**
     * Validate type aggregate
     * This method is used to validate content type aggregate entity.
     * It validates the type itself and every field and attribute attached to
     * the type.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @param \ShiftCommon\Model\Validation\Result
     */
    public function validateTypeAggregate(Type $type)
    {
        $validator = 'ShiftContentNew\Type\TypeValidator';
        $validator = $this->locator->newInstance($validator);
        $result = $validator->validate($type);
        return $result;
    }


    /**
     * Save type
     * Validates and persists content type. May optionally flush transaction.
     * Will return validation result object in case of failed validation.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @param bool $flush
     * @return \ShiftContentNew\Type\Type |
     *  \ShiftCommon\Model\Validation\Result
     */
    public function saveType(Type $type, $flush = true)
    {
        $result = $this->validateTypeAggregate($type);
        if(!$result->isValid())
            return $result;

        return $this->getRepository()->save($type, $flush);
    }


    /**
     * Delete type
     * Removes content type.  May optionally flush transaction.
     *
     * @param \ShiftContentNew\Type\Type $type
     * @param bool $flush
     * @return void
     */
    public function deleteType(Type $type, $flush = true)
    {
        return $this->getRepository()->delete($type, $flush);
    }


    // ------------------------------------------------------------------------

    /*
     * Service dependencies
     */


    /**
     * Set repository
     * Allows to inject arbitrary repository to be used within service.
     *
     * @param \ShiftContentNew\Type\TypeRepository $repository
     * @return \ShiftContentNew\Type\TypeService
     */
    public function setRepository(TypeRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }


    /**
     * Get repository
     * Checks if we already have an injected repository and returns that.
     * Otherwise retrieves one from doctrine.
     *
     * @return \ShiftContentNew\Type\TypeRepository
     */
    public function getRepository()
    {
        if(!$this->repository)
        {
            $em = $this->locator->get('Doctrine')->getEntityManager();
            $this->repository = $em->getRepository('ShiftContentNew\Type\Type');
        }

        return $this->repository;
    }





} //class ends here