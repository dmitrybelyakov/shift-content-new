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
 * @subpackage  Exception
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Exception;

use ShiftContentNew\Exception as ContentException;

/**
 * ShiftContent module domain exception
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Exception
 */

class DomainException
    extends \LogicException
    implements ContentException
{
}