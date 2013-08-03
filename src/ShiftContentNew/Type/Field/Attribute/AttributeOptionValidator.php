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
use Zend\Filter\StringToLower;

use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

use ShiftContentNew\Type\Field\Attribute\Validator\OptionVariableValidator;
use ShiftContentNew\Type\Field\Attribute\Validator\OptionTypeValidator;

/**
 * Attribute option validator
 * Checks to see if attribute option is well-formed and ready for persistence.
 *
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class AttributeOptionValidator extends EntityValidator
{

    public function init(){

        //parent attribute
        $this->addEntity('attribute');
        $this->attribute->notEmpty();

        //name
        $this->addProperty('name');
        $this->name->addFilter(new StripTags);
        $this->name->addFilter(new StringTrim);
        $this->name->addValidator(new NotEmpty);

        //variable
        $this->addProperty('variable');
        $this->variable->addFilter(new StripTags);
        $this->variable->addFilter(new StringTrim);
        $this->variable->addValidator(new NotEmpty);
        $this->variable->addValidator(new OptionVariableValidator);

        //value type
        $this->addProperty('type');
        $this->type->addFilter(new StripTags);
        $this->type->addFilter(new StringTrim);
        $this->type->addFilter(new StringToLower);
        $this->type->addValidator(new NotEmpty);
        $this->type->addValidator(new OptionTypeValidator);


    }

}//class ends here