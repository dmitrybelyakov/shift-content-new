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

use ShiftCommon\Model\Entity\EntityValidator;
use ShiftContentNew\Item\Validator\StatusValidator;
use ShiftContentNew\Item\Validator\DateValidator;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToLower;

use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Alnum;

/**
 * Base content item validator
 * This is base validator for content items. In it's current state it
 * validates only item's meta properties. It should be adjusted with
 * filters/validators for custom item properties
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Item
 */
class ItemValidator extends EntityValidator
{

    /**
     * Init
     * Configure validation rules for item's meta properties.
     * @return \ShiftCommon\Model\Entity\EntityValidator
     */
    public function init()
    {
        //author
        $this->addEntity('author');
        $this->author->notEmpty();

        //content type
        $this->addEntity('contentType');
        $this->contentType->notEmpty();

        //status
        $this->addProperty('status');
        $this->status->addValidator(new StatusValidator);

        //creation date
        $this->addProperty('created');
        $this->created->addValidator(new DateValidator);

        //publication date
        $this->addProperty('publicationDate');
        $this->publicationDate->addValidator(new DateValidator);

        //title
        $this->addProperty('title');
        $this->title->addFilter(new StripTags);
        $this->title->addFilter(new StringTrim);
        $this->title->addValidator(new NotEmpty);
        $this->title->addValidator(new StringLength(
            array("min" => 3, "max" => 250)
        ));

        //slug
        $this->addProperty('slug');
        $this->slug->addFilter(new StripTags);
        $this->slug->addFilter(new StringTrim);
        $this->slug->addFilter(new StringToLower);
        $this->slug->addValidator(new NotEmpty);
        $this->slug->addValidator(new StringLength(
            array("min" => 3, "max" => 250)
        ));
        $this->slug->addValidator(
            'ShiftContentNew\Item\Validator\UniqueSlugValidator' //from locator
        );

    }

} //class ends here