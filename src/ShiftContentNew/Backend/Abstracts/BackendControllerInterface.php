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
 * @package     ShiftYeoman
 * @subpackage  Backend
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Backend\Abstracts;

use ShiftKernel\Controller\Backend\BackendControllerInterface as BaseBackend;

/**
 * Backend controller interface
 * If you mark your controller with this interface then a listener will catch
 * that at dispatch time and configure backend for the module.
 *
 * @category    Projectshift
 * @package     ShiftYeoman
 * @subpackage  ShiftYeoman
 */
interface BackendControllerInterface extends BaseBackend
{
}