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
 * @subpackage  Backend
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Backend\Controller;

use ShiftKernel\Backend\AbstractAngularBackendController;
use Zend\Navigation\Navigation;

/**
 * Abstract content backend
 * This hold common functionality for content backend controllers, like
 * setting up proper layout and injecting navigation.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Backend
 */
abstract class AbstractContentBackend extends AbstractAngularBackendController
{

    /**
     * Init
     * Initialize controller for backend section
     * @return void
     */
    protected function init()
    {
        parent::init();

//        $nav = $this->layout()->navigation;
//        $subnav = $nav->findOneBy('route', 'backend-module-content-new');
//        $this->layout()->navigation = new Navigation($subnav);
//
//        $crumbs = new Navigation($subnav);
//        $this->layout()->crumbsNavigation
//            ->findOneBy('label', 'Manage content')
//            ->addPages($crumbs);
    }




} //class ends here