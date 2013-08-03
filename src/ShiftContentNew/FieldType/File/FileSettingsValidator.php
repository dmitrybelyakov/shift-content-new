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
namespace ShiftContentNew\FieldType\File;
use ShiftCommon\Model\Entity\EntityValidator;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;

use Zend\Validator\NotEmpty;
use ShiftContentNew\FieldType\File\Validator\DestinationValidator;
use ShiftContentNew\FieldType\File\Validator\ModeValidator;

/**
 * File settings validator
 * Checks that file field settings are correct.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Type
 */
class FileSettingsValidator extends EntityValidator
{

    /**
     * Init
     * Configures validation rules for file settings entity.
     *
     * @return void
     */
    public function init()
    {
        //destination
        $this->addProperty('destination');
        $this->destination->addFilter(new StripTags);
        $this->destination->addFilter(new StringTrim);
        $this->destination->addValidator(new NotEmpty);
        $this->destination->addValidator(new DestinationValidator);


        //mode
        $this->addProperty('mode');
        $this->mode->addFilter(new StripTags);
        $this->mode->addFilter(new StringTrim);
        $this->mode->addValidator(new NotEmpty);
        $this->mode->addValidator(new ModeValidator);

    }



} //class ends here