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
 * @subpackage  Controller
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Api;
use Zend\View\Model\JsonModel;
use ShiftContentNew\Api\AbstractApi;

use ShiftContentNew\Type\Type;
use ShiftCommon\Model\Validation\Result as Errors;

/**
 * Field types api controller
 * A read-only api controller exposing configured content type fields that
 * are available for assigning to content types.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Controller
 */
class FieldTypesApi extends AbstractApi
{
    /**
     * List action
     * GET: returns a list of configured content fields.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function listAction()
    {
        $factory = 'ShiftContentNew\FieldType\FieldTypeFactory';
        $factory = $this->locator->get($factory);
        $types = $factory->getFieldTypes();

        //get: return content types
        if($this->getRequest()->isGet())
        {
            $fieldTypes = array();
            foreach($types as $shortName => $type)
            {
                $fieldTypes[] = array(
                    'shortName' => $shortName,
                    'name' => $type->getName(),
                    'description' => $type->getDescription(),
                );
            }

            return new JsonModel($fieldTypes);
        }

        //otherwise return not allowed
        return $this->notAllowedAction();
    }


} //class ends here