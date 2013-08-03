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
namespace ShiftContentNew\Type\Field\Attribute;

use ShiftCommon\Model\Entity\EntityValidator;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

use Zend\Validator\Alnum;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

use ShiftContentNew\Type\Field\Attribute\Validator\AttributeTypeValidator;
use ShiftContentNew\Type\Field\Attribute\Validator\AttributeClassValidator;

/**
 * Attribute validator
 * This encapsulates rules for validating field attributes before persistence.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeValidator extends EntityValidator
{
    /**
     * Service locator instance
     * @var \Zend\Di\Locator
     */
    protected $locator;


    /**
     * Init
     * Initializes validator and defines rules for validating entity
     * properties.
     *
     * @return EntityValidator|void
     */
    public function init()
    {
        //state validator (validates against configuration)
        $state = 'ShiftContentNew\Type\Field\Attribute\Validator';
        $state .= '\AttributeStateValidator';
        $this->addStateValidator('attributeConfig', $state);

        //type
        $this->addProperty('type');
        $this->type->addFilter(new StripTags);
        $this->type->addFilter(new StringTrim);
        $this->type->addValidator(new AttributeTypeValidator);

        //class name must be set and be valid
        $this->addProperty('className');
        $this->className->addFilter(new StripTags);
        $this->className->addFilter(new StringTrim);
        $this->className->addValidator(new NotEmpty);
        $this->className->addValidator(new AttributeClassValidator);

        //parent field
        $this->addEntity('field');
        $this->field->notEmpty();

        //option collection
        $this->addCollection('options');
        $this->options->setElementValidator(
            'ShiftContentNew\Type\Field\Attribute\AttributeOptionValidator'
        );

    }



} //class ends here