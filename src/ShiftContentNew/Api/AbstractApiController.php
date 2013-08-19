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

} //class ends here