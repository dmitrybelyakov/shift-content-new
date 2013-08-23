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

use ShiftCommon\Model\Entity\EntityValidator;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

use Zend\Validator\Alnum;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

/**
 * Content type validator
 * This encapsulates rules for validating content types before persistence.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class TypeValidator extends EntityValidator
{

    /**
     * Init
     * Initializes validator and defines rules for validating entity
     * properties.
     *
     * @return EntityValidator|void
     */
    public function init()
    {
        //name
        $this->addProperty('name');
        $this->name->addFilter(new StripTags);
        $this->name->addFilter(new StringTrim);
        $this->name->addValidator(new NotEmpty);
        $this->name->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->name->addValidator(new StringLength(
            array('min' => '3', 'max' => 250)
        ));
        $this->name->addValidator(
            'ShiftContentNew\Type\Validator\UniqueNameValidator'
        );

        //description
        $this->addProperty('description');
        $this->description->addFilter(new StripTags);
        $this->description->addFilter(new StringTrim);
        $this->description->addValidator(new StringLength(array('max' => 250)));
        $this->description->addValidator(new NotEmpty);


        //fields validator
        $this->addCollection('fields');
        $this->fields->setElementValidator(
            'ShiftContentNew\Type\Field\FieldValidator'
        );
    }



} //class ends here