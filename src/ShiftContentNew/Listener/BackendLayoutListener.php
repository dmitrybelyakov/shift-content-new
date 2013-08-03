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
 * @subpackage  Listener
 */

/**
 * @namespace
 */
namespace ShiftContentNew\Listener;

use Zend\EventManager\ListenerAggregate as ListenerAggregateInterface;
use Zend\EventManager\EventCollection;
use Zend\Mvc\MvcEvent;

use ShiftContentNew\Backend\Abstracts\BackendControllerInterface;

/**
 * Backend layout listener
 * This is used to catch backend controllers dispatch and setup backend
 * layout to inject proper navigation.
 *
 * @category    Projectshift
 * @package     ShiftContentNew
 * @subpackage  Listener
 */
class BackendLayoutListener implements ListenerAggregateInterface
{
    /**
     * A collection of attached listeners
     * @var array
     */
    protected $listeners = array();

    /**
     * Attach
     * Attaches this listener to an event collection.
     *
     * @param \Zend\EventManager\EventCollection $events
     * @return void
     */
    public function attach(EventCollection $events)
    {
        //inject navigation
        $events->attach(
            'dispatch',
            array($this, 'setLayout'),
            -200
        );
    }

    /**
     * Detach
     * Removes this listener from event collection.
     *
     * @param \Zend\EventManager\EventCollection $events
     * @return void
     */
    public function detach(EventCollection $events)
    {
        foreach($this->listeners as $index => $listener)
        {
            if($events->detach($listener))
                unset($this->listeners[$index]);
        }
    }

    /**
     * Set layout
     * Sets layout for module backend to angular webapp
     *
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @return void
     */
    public function setLayout(MvcEvent $mvcEvent)
    {
        $controller = $mvcEvent->getTarget();
        if(!$controller instanceof BackendControllerInterface)
            return;

        $viewModel = $mvcEvent->getViewModel();
        $viewModel->setTemplate('shiftconetnt-new.backend.layout');

        return;
    }

} //class ends here