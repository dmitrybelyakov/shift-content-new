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
 * @subpackage  Api
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Api;

use ShiftKernel\Controller\Api\AbstractApi as BaseApiController;
use Zend\View\Model\JsonModel;


/**
 * Abstract api controller
 * All api controllers must be extended from this one to properly respond
 * to rest api requests.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Api
 */
abstract class AbstractApiController extends BaseApiController
{

    /**
     * Api response template
     * This will be used if you try to access api with a regular browser.
     * @var string
     */
    protected $responseTemplate = 'shiftcontent-new.api.response';

    /**
     * Delay
     * A sleeper sugar to delay api response. May be useful to test frontend
     * apps with near natural api delya.
     *
     * @param int $duration
     * @return void
     */
    protected function delay($duration = 2)
    {
        sleep($duration);
    }


    /**
     * Failed validation
     * Sets response code to 409 and returns unsupported message.
     *
     * @param array $errors
     * @return \Zend\View\Model\JsonModel
     */
    public function failedValidationAction(array $errors)
    {
        $this->getResponse()->setStatusCode(409);
        $vm = new JsonModel($errors);
        $vm->setTemplate($this->responseTemplate);
        return $vm;
    }


} //class ends here