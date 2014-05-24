<?php
return array(

    /*
     * Content backend
     * This uses a catch-all route ro direct to index controller.
     * Firther routing is done by frontend webapp
     */
    'backend-module-content-new' => array(
        'type' => 'Zend\Mvc\Router\Http\Regex',
        'options' => array(
            'regex' => '/backend/modules/content-new/(?<subroute>[^?*:;{}]+)?',
            'spec'  => '/backend/modules/content-new/%subroute%',
            'defaults' => array(
                'controller' => 'ShiftContentNew\Backend\Controller\Index',
                'action' => 'index',
                'subroute' => false,
            ),
            'restrict' => array('shiftkernel.canAccessBackend')
        ),
        'may_terminate' => true,
        'child_routes' => array()
    ), //content backend


);