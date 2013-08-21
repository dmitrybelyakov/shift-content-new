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
use ShiftContentNew\Api\AbstractApiController;


/**
 * Content type api controller
 * Responsible for handling requests to manage content types.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Controller
 */
class ContentTypeApiController extends AbstractApiController
{
    /**
     * List action
     * GET: returns a list of existing types.
     * POST: creates a new content type
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function listAction()
    {
        //get: return content types
        if($this->getRequest()->isGet())
        {
            $service = 'ShiftContentNew\Type\TypeService';
            $service = $this->locator->get($service);
            $types = $service->getTypes();

            $jsonTypes = array();
            foreach($types as $type)
                $jsonTypes[] = $type->toArray();

            return new JsonModel($jsonTypes);
        }

        //post validate and create type
        if($this->getRequest()->isPost())
        {
            die('me is post action');
        }

        //otherwise return not allowed
        return $this->notAllowedAction();
    }


    /**
     * Type action
     * GET: returns single content type
     * POST: updates content type
     * DELETE: remove content type
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function typeAction()
    {
        die('me is type action');
    }

} //class ends here